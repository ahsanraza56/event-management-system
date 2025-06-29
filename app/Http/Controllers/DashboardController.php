<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->adminDashboard();
        }
        
        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'active')->count(),
            'total_bookings' => Booking::count(),
            'total_users' => User::where('role', 'user')->count(),
            'recent_bookings' => Booking::with(['user', 'event'])
                ->latest()
                ->take(5)
                ->get(),
            'upcoming_events' => Event::where('status', 'active')
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function userDashboard()
    {
        $user = Auth::user();
        
        $data = [
            'my_bookings' => $user->bookings()
                ->with('event')
                ->latest()
                ->take(5)
                ->get(),
            'upcoming_events' => Event::where('status', 'active')
                ->where('date', '>=', now()->toDateString())
                ->orderBy('date')
                ->take(6)
                ->get(),
        ];

        return view('dashboard', compact('data'));
    }
} 