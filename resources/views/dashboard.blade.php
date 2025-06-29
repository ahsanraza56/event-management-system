@extends('layouts.app')

@section('title', 'Dashboard - Event Management System')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>My Recent Bookings</h5>
            </div>
            <div class="card-body">
                @if($data['my_bookings']->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($data['my_bookings'] as $booking)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $booking->event->title ?? 'Event Deleted' }}</strong><br>
                                    <small class="text-muted">Booked on {{ $booking->created_at->format('M d, Y') }}</small>
                                </div>
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-ticket-alt"></i> View Ticket
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center">No recent bookings.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Events</h5>
            </div>
            <div class="card-body">
                @if($data['upcoming_events']->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($data['upcoming_events'] as $event)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $event->title }}</strong><br>
                                    <small class="text-muted">{{ $event->date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</small>
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted text-center">No upcoming events.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 