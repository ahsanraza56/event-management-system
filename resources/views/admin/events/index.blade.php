@extends('layouts.app')

@section('title', 'Manage Events - Admin Panel')

@push('styles')
<style>
    .event-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 15px;
        overflow: hidden;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .event-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }
    .event-image-placeholder {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
    .event-meta {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    .action-buttons .btn {
        flex: 1;
        min-width: 40px;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">
            <i class="fas fa-calendar-alt me-2"></i>Manage Events
        </h2>
        <p class="text-muted mb-0">Manage and organize your events</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Event
    </a>
</div>

@if($events->count() > 0)
    <div class="row">
        @foreach($events as $event)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card event-card h-100">
                    <div class="position-relative">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" class="event-image" alt="{{ $event->title }}">
                        @else
                            <div class="event-image-placeholder">
                                <i class="fas fa-calendar-alt fa-3x"></i>
                            </div>
                        @endif
                        <span class="badge status-badge bg-{{ $event->status === 'active' ? 'success' : ($event->status === 'inactive' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ Str::limit($event->title, 40) }}</h5>
                        
                        <div class="event-meta mb-3">
                            <div class="mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ Str::limit($event->venue, 30) }}
                            </div>
                            <div class="mb-1">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $event->date->format('M d, Y') }}
                            </div>
                            <div class="mb-1">
                                <i class="fas fa-clock me-1"></i>
                                {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                            </div>
                            <div class="mb-1">
                                <i class="fas fa-users me-1"></i>
                                {{ $event->capacity }} capacity
                            </div>
                            <div>
                                <i class="fas fa-dollar-sign me-1"></i>
                                PKR {{ number_format($event->price, 2) }}
                            </div>
                            <div>
                                <i class="fas fa-chair me-1"></i>
                                @if($event->hasSeatSelection())
                                    <span class="text-success">{{ $event->seats()->count() }} seats configured</span>
                                @else
                                    <span class="text-muted">No seat selection</span>
                                @endif
                            </div>
                        </div>
                        
                        <p class="card-text text-muted">
                            {{ Str::limit($event->description, 80) }}
                        </p>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="action-buttons">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($event->hasSeatSelection())
                                <a href="{{ route('events.seat-selection', $event) }}" class="btn btn-sm btn-outline-warning" title="Manage Seats">
                                    <i class="fas fa-chair"></i>
                                </a>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Generate Seats" 
                                        onclick="generateSeats({{ $event->id }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @endif
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" 
                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Events Found</h4>
            <p class="text-muted">Start by creating your first event!</p>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create First Event
            </a>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
function generateSeats(eventId) {
    if (confirm('Generate seats for this event? This will create VIP, Main, and Balcony sections.')) {
        fetch(`/admin/events/${eventId}/generate-seats`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Seats generated successfully!');
                location.reload();
            } else {
                alert('Error generating seats: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating seats');
        });
    }
}
</script>
@endpush 