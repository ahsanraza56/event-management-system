@extends('layouts.app')

@section('title', 'My Bookings - Event Management System')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>My Bookings</h2>
    </div>
</div>
@if($bookings->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Ticket #</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->event->title ?? 'Event Deleted' }}</td>
                        <td>{{ $booking->event->date ? $booking->event->date->format('M d, Y') : '-' }}</td>
                        <td>{{ $booking->event->venue ?? '-' }}</td>
                        <td>{{ $booking->ticket_number }}</td>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" title="View Ticket">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($booking->status !== 'cancelled')
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel Booking" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $bookings->links() }}
    </div>
@else
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle me-2"></i>No bookings found.
    </div>
@endif
@endsection 