@extends('layouts.app')

@section('title', 'Admin Dashboard - Event Management System')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0">
            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
        </h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_events'] }}</h4>
                        <p class="mb-0">Total Events</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['active_events'] }}</h4>
                        <p class="mb-0">Active Events</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_bookings'] }}</h4>
                        <p class="mb-0">Total Bookings</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ticket-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                        <p class="mb-0">Registered Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Bookings
                </h5>
            </div>
            <div class="card-body">
                @if($stats['recent_bookings']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_bookings'] as $booking)
                                    <tr>
                                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                        <td>{{ Str::limit($booking->event->title ?? 'Event Deleted', 20) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $booking->created_at->format('M d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No recent bookings</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                </h5>
            </div>
            <div class="card-body">
                @if($stats['upcoming_events']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stats['upcoming_events'] as $event)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <small class="text-muted">
                                        {{ $event->date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                    </small>
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No upcoming events</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Add Event
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-calendar-alt me-2"></i>Manage Events
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-list me-2"></i>View Bookings
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('events.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-eye me-2"></i>View Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 