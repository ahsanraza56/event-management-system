@extends('layouts.app')

@section('title', 'My Ticket - Event Management System')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card ticket">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>My Ticket</h4>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Event</dt>
                    <dd class="col-sm-8">{{ $booking->event->title ?? 'Event Deleted' }}</dd>

                    <dt class="col-sm-4">Venue</dt>
                    <dd class="col-sm-8">{{ $booking->event->venue ?? '-' }}</dd>

                    <dt class="col-sm-4">Date & Time</dt>
                    <dd class="col-sm-8">
                        {{ $booking->event->date->format('M d, Y') ?? '-' }} at {{ \Carbon\Carbon::parse($booking->event->time)->format('g:i A') ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">Ticket Number</dt>
                    <dd class="col-sm-8">{{ $booking->ticket_number }}</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </dd>

                    @if($booking->selected_seats && count($booking->selected_seats) > 0)
                        <dt class="col-sm-4">Selected Seats</dt>
                        <dd class="col-sm-8">
                            @foreach($booking->seats() as $seat)
                                <span class="badge bg-primary me-1 mb-1">
                                    {{ ucfirst($seat->section) }} - {{ $seat->seat_number }}
                                </span>
                            @endforeach
                        </dd>
                    @endif
                </dl>
                <hr>
                <div class="qr-code">
                    @if(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($booking->getQrCodeString()) !!}
                    @else
                        <div class="alert alert-warning">QR code package not installed. Please install <code>simplesoftwareio/simple-qrcode</code>.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 