@extends('layouts.app')

@section('title', 'Events - Event Management System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
            </h2>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Event
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>

@if($events->count() > 0)
    <div class="row">
        @foreach($events as $event)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card event-card h-100">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->venue }}
                        </p>
                        <p class="card-text text-muted">
                            <i class="fas fa-calendar me-1"></i>{{ $event->date->format('M d, Y') }}
                        </p>
                        <p class="card-text text-muted">
                            <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                        </p>
                        <p class="card-text">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">PKR {{ number_format($event->price, 2) }}</span>
                            <span class="badge bg-{{ $event->isFull() ? 'danger' : 'success' }}">
                                {{ $event->available_seats }} seats left
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-grid">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Events Available</h4>
                    <p class="text-muted">Check back later for upcoming events!</p>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection 