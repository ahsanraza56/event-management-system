@extends('layouts.app')

@section('title', $event->title . ' - Event Management System')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            @if($event->image)
                <img src="{{ Storage::url($event->image) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 400px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-calendar-alt fa-4x text-muted"></i>
                </div>
            @endif
            
            <div class="card-body">
                <h2 class="card-title">{{ $event->title }}</h2>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i><strong>Venue:</strong>
                        </p>
                        <p>{{ $event->venue }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar me-2"></i><strong>Date:</strong>
                        </p>
                        <p>{{ $event->date->format('l, F d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">
                            <i class="fas fa-clock me-2"></i><strong>Time:</strong>
                        </p>
                        <p>{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">
                            <i class="fas fa-dollar-sign me-2"></i><strong>Price:</strong>
                        </p>
                        <p class="h5 text-primary">PKR {{ number_format($event->price, 2) }}</p>
                    </div>
                </div>
                
                <h5>Description</h5>
                <p class="card-text">{{ $event->description }}</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">
                            <i class="fas fa-users me-2"></i><strong>Capacity:</strong>
                        </p>
                        <p>{{ $event->capacity }} people</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">
                            <i class="fas fa-ticket-alt me-2"></i><strong>Available Seats:</strong>
                        </p>
                        <p class="h6 text-{{ $event->isFull() ? 'danger' : 'success' }}">
                            {{ $event->available_seats }} seats left
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Book This Event
                </h5>
            </div>
            <div class="card-body">
                @auth
                    @if($event->isActive() && !$event->isFull())
                        @php
                            $existingBooking = auth()->user()->bookings()
                                ->where('event_id', $event->id)
                                ->where('status', '!=', 'cancelled')
                                ->first();
                        @endphp
                        
                        @if($existingBooking)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                You already have a booking for this event.
                                <a href="{{ route('bookings.show', $existingBooking) }}" class="alert-link">View Ticket</a>
                            </div>
                        @else
                            @if($event->hasSeatSelection())
                                <div class="alert alert-info">
                                    <i class="fas fa-chair me-2"></i>
                                    This event has seat selection available.
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('events.seat-selection', $event) }}" class="btn btn-primary">
                                        <i class="fas fa-chair me-2"></i>Select Seats
                                    </a>
                                    <small class="text-muted text-center">Choose your preferred seats</small>
                                </div>
                            @else
                                <form action="{{ route('bookings.store', $event) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Number of Tickets</label>
                                        <select class="form-select" id="quantity" name="quantity" required>
                                            <option value="1">1 Ticket</option>
                                            <option value="2">2 Tickets</option>
                                            <option value="3">3 Tickets</option>
                                            <option value="4">4 Tickets</option>
                                            <option value="5">5 Tickets</option>
                                        </select>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-ticket-alt me-2"></i>Book Now
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            @if(!$event->isActive())
                                This event is not available for booking.
                            @else
                                This event is fully booked.
                            @endif
                        </div>
                    @endif
                @else
                    <div class="text-center">
                        <p class="text-muted">Please login to book this event.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <p class="mt-2">
                            <small class="text-muted">Don't have an account?</small><br>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </p>
                    </div>
                @endauth
            </div>
        </div>
        
        @auth
            @if(auth()->user()->isAdmin())
                <div class="card mt-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-cog me-2"></i>Admin Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Edit Event
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100" 
                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                    <i class="fas fa-trash me-1"></i>Delete Event
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection 