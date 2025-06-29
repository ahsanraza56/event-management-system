<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SeatSelectionController extends Controller
{
    /**
     * Show seat selection page for an event
     */
    public function show(Event $event)
    {
        // Check if event has seat selection enabled
        if (!$event->hasSeatSelection()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event does not have seat selection available.');
        }

        // Get all seats for the event
        $seats = $event->seats()->orderBy('row')->orderBy('seat_number')->get();
        
        // Group seats by section and row
        $seatMap = $seats->groupBy('section')->map(function ($sectionSeats) {
            return $sectionSeats->groupBy('row');
        });

        return view('events.seat-selection', compact('event', 'seatMap'));
    }

    /**
     * Get available seats for an event (AJAX)
     */
    public function getAvailableSeats(Event $event): JsonResponse
    {
        $seats = $event->seats()
            ->select('id', 'seat_number', 'row', 'section', 'status', 'price')
            ->get()
            ->map(function ($seat) {
                return [
                    'id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                    'row' => $seat->row,
                    'section' => $seat->section,
                    'status' => $seat->status,
                    'price' => $seat->getEffectivePriceAttribute(),
                    'is_available' => $seat->isAvailable(),
                ];
            });

        return response()->json([
            'seats' => $seats,
            'total_seats' => $seats->count(),
            'available_seats' => $seats->where('is_available', true)->count(),
        ]);
    }

    /**
     * Reserve seats temporarily (AJAX)
     */
    public function reserveSeats(Request $request, Event $event): JsonResponse
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'integer|exists:seats,id',
        ]);

        $seatIds = $request->input('seat_ids');
        
        // Check if all seats are available
        $seats = Seat::whereIn('id', $seatIds)
            ->where('event_id', $event->id)
            ->where('status', 'available')
            ->get();

        if ($seats->count() !== count($seatIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Some seats are no longer available.',
            ], 400);
        }

        // Temporarily reserve seats (they will be released after 10 minutes)
        Seat::whereIn('id', $seatIds)->update(['status' => 'reserved']);

        // Schedule seat release after 10 minutes
        // In a production app, you'd use a job queue for this
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Seats reserved successfully.',
            'reserved_seats' => $seatIds,
        ]);
    }

    /**
     * Release reserved seats (AJAX)
     */
    public function releaseSeats(Request $request, Event $event): JsonResponse
    {
        $request->validate([
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'integer|exists:seats,id',
        ]);

        $seatIds = $request->input('seat_ids');

        // Release seats back to available
        Seat::whereIn('id', $seatIds)
            ->where('event_id', $event->id)
            ->where('status', 'reserved')
            ->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Seats released successfully.',
        ]);
    }
}
