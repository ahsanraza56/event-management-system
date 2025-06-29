@extends('layouts.app')

@section('title', 'Booking Details - Admin Panel')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Booking Details</h4>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">User</dt>
                    <dd class="col-sm-8">{{ $booking->user->name ?? 'N/A' }} ({{ $booking->user->email ?? '' }})</dd>

                    <dt class="col-sm-4">Event</dt>
                    <dd class="col-sm-8">{{ $booking->event->title ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Ticket Number</dt>
                    <dd class="col-sm-8">{{ $booking->ticket_number }}</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </dd>

                    <dt class="col-sm-4">Booked At</dt>
                    <dd class="col-sm-8">{{ $booking->created_at->format('M d, Y H:i') }}</dd>
                </dl>

                <hr>
                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="row g-3 align-items-center">
                    @csrf
                    @method('PATCH')
                    <div class="col-auto">
                        <label for="status" class="col-form-label">Change Status:</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" id="status" name="status">
                            <option value="pending" @if($booking->status == 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if($booking->status == 'confirmed') selected @endif>Confirmed</option>
                            <option value="cancelled" @if($booking->status == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 