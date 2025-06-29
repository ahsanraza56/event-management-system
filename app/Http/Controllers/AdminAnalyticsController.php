<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\User;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $this->authorize('admin');

        // Basic Statistics
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::where('status', 'active')->count(),
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->get()->sum('total_amount'),
        ];

        // Monthly Booking Trends (Last 6 months) - MySQL compatible
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Top Events by Bookings
        $topEvents = Event::withCount(['bookings' => function($query) {
            $query->where('status', 'confirmed');
        }])
        ->orderBy('bookings_count', 'desc')
        ->take(5)
        ->get();

        // Recent Activity
        $recentBookings = Booking::with(['user', 'event'])
            ->latest()
            ->take(10)
            ->get();

        // Revenue by Month - MySQL compatible
        $monthlyRevenue = Booking::where('status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->get()
            ->groupBy(function($booking) {
                return $booking->created_at->format('n'); // Month number
            })
            ->map(function($bookings) {
                return $bookings->sum('total_amount');
            })
            ->toArray();

        // Event Status Distribution
        $eventStatus = Event::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Booking Status Distribution
        $bookingStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $totalRevenue = Booking::where('status', 'confirmed')->get()->sum('total_amount');
        
        // Seat analytics
        $totalSeats = Seat::count();
        $availableSeats = Seat::where('status', 'available')->count();
        $bookedSeats = Seat::where('status', 'booked')->count();
        $reservedSeats = Seat::where('status', 'reserved')->count();
        $seatUtilization = $totalSeats > 0 ? round(($bookedSeats / $totalSeats) * 100, 2) : 0;
        
        // Seat section breakdown
        $seatSections = Seat::selectRaw('section, COUNT(*) as total, SUM(CASE WHEN status = "booked" THEN 1 ELSE 0 END) as booked')
            ->groupBy('section')
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'monthlyBookings',
            'topEvents',
            'recentBookings',
            'monthlyRevenue',
            'eventStatus',
            'bookingStatus',
            'totalBookings',
            'confirmedBookings',
            'cancelledBookings',
            'totalRevenue',
            'totalSeats',
            'availableSeats',
            'bookedSeats',
            'reservedSeats',
            'seatUtilization',
            'seatSections'
        ));
    }
} 