<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'active')
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->paginate(12);
        
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function adminIndex()
    {
        $this->authorize('admin');
        
        $events = Event::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $this->authorize('admin');
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image'] = $imagePath;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        $this->authorize('admin');
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('admin');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,cancelled',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image'] = $imagePath;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $this->authorize('admin');
        
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Generate seats for a specific event
     */
    public function generateSeats(Event $event): JsonResponse
    {
        if ($event->seats()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Event already has seats configured.'
            ]);
        }

        $sections = [
            'vip' => [
                'price' => 5000,
                'rows' => ['VIP1', 'VIP2', 'VIP3'],
                'seats_per_row' => 8,
            ],
            'main' => [
                'price' => 3000,
                'rows' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
                'seats_per_row' => 12,
            ],
            'balcony' => [
                'price' => 2000,
                'rows' => ['BA', 'BB', 'BC', 'BD'],
                'seats_per_row' => 10,
            ],
        ];

        $created = 0;
        foreach ($sections as $section => $config) {
            foreach ($config['rows'] as $row) {
                for ($i = 1; $i <= $config['seats_per_row']; $i++) {
                    $seatNumber = $row . $i;
                    Seat::create([
                        'event_id' => $event->id,
                        'seat_number' => $seatNumber,
                        'row' => $row,
                        'section' => $section,
                        'status' => 'available',
                        'price' => $config['price'],
                    ]);
                    $created++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Generated $created seats for {$event->title}",
            'seats_created' => $created
        ]);
    }
} 