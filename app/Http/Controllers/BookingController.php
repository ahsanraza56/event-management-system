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
        
        if ($event->hasSeatSelection()) {
            // Validate seat selection
            if (empty($selectedSeats)) {
                return back()->with('error', 'Please select seats for this event.');
            }

            if (count($selectedSeats) !== $request->input('quantity')) {
                return back()->with('error', 'Number of selected seats must match the quantity.');
            }

            // Check if selected seats are available
            $seats = Seat::whereIn('id', $selectedSeats)
                ->where('event_id', $event->id)
                ->where('status', 'available')
                ->get();

            if ($seats->count() !== count($selectedSeats)) {
                return back()->with('error', 'Some selected seats are no longer available.');
            }

            // Mark seats as booked
            Seat::whereIn('id', $selectedSeats)->update(['status' => 'booked']);
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'status' => 'confirmed',
            'quantity' => $request->input('quantity', 1),
            'selected_seats' => $selectedSeats,
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
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
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