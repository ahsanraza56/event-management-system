@extends('layouts.app')

@section('title', 'Edit Event - Admin Panel')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Event</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $event->date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control" id="time" name="time" value="{{ old('time', $event->time) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue</label>
                        <input type="text" class="form-control" id="venue" name="venue" value="{{ old('venue', $event->venue) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $event->capacity) }}" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price (PKR)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $event->price) }}" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Event Image</label>
                        @if($event->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($event->image) }}" alt="Current Image" style="max-width: 200px; max-height: 120px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" @if(old('status', $event->status) == 'active') selected @endif>Active</option>
                            <option value="inactive" @if(old('status', $event->status) == 'inactive') selected @endif>Inactive</option>
                            <option value="cancelled" @if(old('status', $event->status) == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 