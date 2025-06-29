<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Seat;
use App\Mail\BookingConfirmation;
use App\Mail\BookingStatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request, Event $event)
    {
        // Debug logging
        Log::info('Booking request received', [
            'event_id' => $event->id,
            'all_request_data' => $request->all(),
            'quantity' => $request->input('quantity'),
            'selected_seats' => $request->input('selected_seats'),
            'selected_seats_type' => gettype($request->input('selected_seats')),
            'selected_seats_count' => is_array($request->input('selected_seats')) ? count($request->input('selected_seats')) : 'not_array'
        ]);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:5',
            'selected_seats' => 'nullable|array',
            'selected_seats.*' => 'integer|exists:seats,id',
        ]);

        // Check if event is available
        if (!$event->isActive()) {
            return back()->with('error', 'This event is not available for booking.');
        }

        if ($event->isFull()) {
            return back()->with('error', 'This event is fully booked.');
        }

        // Check if user already has a booking for this event
        $existingBooking = Auth::user()->bookings()
            ->where('event_id', $event->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'You already have a booking for this event.');
        }

        // Handle seat selection
        $selectedSeats = $request->input('selected_seats', []);
        $quantity = (int) $request->input('quantity', 1);
        $totalAmount = 0;
        
        // Additional debug logging
        Log::info('Processing booking', [
            'selectedSeats' => $selectedSeats,
            'selectedSeatsType' => gettype($selectedSeats),
            'selectedSeatsCount' => is_array($selectedSeats) ? count($selectedSeats) : 'not_array',
            'quantity' => $quantity,
            'quantityType' => gettype($quantity),
            'event_has_seat_selection' => $event->hasSeatSelection()
        ]);
        
        if ($event->hasSeatSelection()) {
            // Validate seat selection
            if (empty($selectedSeats)) {
                Log::warning('No seats selected for event with seat selection');
                return back()->with('error', 'Please select seats for this event.');
            }

            // Ensure selected_seats is an array and count matches quantity
            $selectedSeatsCount = is_array($selectedSeats) ? count($selectedSeats) : 0;
            
            Log::info('Seat validation check', [
                'selectedSeatsCount' => $selectedSeatsCount,
                'selectedSeatsCountType' => gettype($selectedSeatsCount),
                'quantity' => $quantity,
                'quantityType' => gettype($quantity),
                'match' => $selectedSeatsCount == $quantity, // Use loose comparison
                'strictMatch' => $selectedSeatsCount === $quantity // Use strict comparison
            ]);
            
            // Use loose comparison to handle string/integer differences
            if ($selectedSeatsCount != $quantity) {
                Log::error('Seat count mismatch', [
                    'selectedSeatsCount' => $selectedSeatsCount,
                    'quantity' => $quantity,
                    'selectedSeats' => $selectedSeats
                ]);
                return back()->with('error', "Number of selected seats ({$selectedSeatsCount}) must match the quantity ({$quantity}).");
            }

            // Check if selected seats are available
            $seats = Seat::whereIn('id', $selectedSeats)
                ->where('event_id', $event->id)
                ->where('status', 'available')
                ->get();

            if ($seats->count() !== count($selectedSeats)) {
                return back()->with('error', 'Some selected seats are no longer available.');
            }

            // Calculate total amount from selected seats
            $totalAmount = $seats->sum('price');

            // Mark seats as booked
            Seat::whereIn('id', $selectedSeats)->update(['status' => 'booked']);
        } else {
            // Calculate total amount from event price and quantity
            $totalAmount = $event->price * $quantity;
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'confirmed',
            'quantity' => $quantity,
            'selected_seats' => $selectedSeats,
            'total_amount' => $totalAmount,
        ]);

        Log::info('Booking created successfully', [
            'booking_id' => $booking->id,
            'quantity' => $booking->quantity,
            'selected_seats' => $booking->selected_seats,
            'total_amount' => $booking->total_amount
        ]);

        // Send confirmation email
        try {
            Mail::to(Auth::user()->email)->send(new BookingConfirmation($booking));
        } catch (\Exception $e) {
            // Log error but don't fail the booking
            \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking confirmed! Your ticket has been generated and confirmation email sent.');
    }

    public function show(Booking $booking)
    {
        try {
            // Ensure user can only view their own bookings
            if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
                abort(403, 'You are not authorized to view this booking.');
            }

            // Load relationships to prevent N+1 queries
            $booking->load(['user', 'event']);

            // Check if booking has required data
            if (!$booking->event) {
                return back()->with('error', 'This booking is associated with an event that no longer exists.');
            }

            return view('bookings.show', compact('booking'));
        } catch (\Exception $e) {
            \Log::error('Error showing booking: ' . $e->getMessage(), [
                'booking_id' => $booking->id ?? 'unknown',
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            return back()->with('error', 'Unable to load booking details. Please try again.');
        }
    }

    public function cancel(Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($booking->isCancelled()) {
            return back()->with('error', 'This booking is already cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    // Admin methods
    public function adminIndex()
    {
        $this->authorize('admin');
        
        $bookings = Booking::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.bookings.index', compact('bookings'));
    }

    public function adminShow(Booking $booking)
    {
        $this->authorize('admin');
        return view('admin.bookings.show', compact('booking'));
    }

    public function adminUpdate(Request $request, Booking $booking)
    {
        $this->authorize('admin');
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        // Update booking status
        $booking->update(['status' => $newStatus]);

        // Send status update email to user
        try {
            Mail::to($booking->user->email)->send(new BookingStatusUpdate($booking, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            // Log error but don't fail the status update
            \Log::error('Failed to send booking status update email: ' . $e->getMessage());
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking status updated successfully and notification email sent to user.');
    }
} 