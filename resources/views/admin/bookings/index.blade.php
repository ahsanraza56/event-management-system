@extends('layouts.app')

@section('title', 'All Bookings - Admin Panel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-list me-2"></i>All Bookings
    </h2>
</div>

@if($bookings->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Ticket #</th>
                    <th>Status</th>
                    <th>Booked At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->event->title ?? 'N/A' }}</td>
                        <td>{{ $booking->ticket_number }}</td>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>{{ $booking->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
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