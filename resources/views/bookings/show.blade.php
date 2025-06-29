@extends('layouts.app')

@section('title', 'My Ticket - Event Management System')

@section('content')
<style>
.ticket {
    border: 2px solid #007bff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
}

.ticket .card-header {
    border-radius: 13px 13px 0 0;
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.qr-code {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
}

dl.row dt {
    font-weight: 600;
    color: #495057;
}

dl.row dd {
    color: #212529;
}
</style>

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
                        @if($booking->event && $booking->event->date)
                            {{ $booking->event->date->format('M d, Y') }} at 
                            @if($booking->event->time)
                                {{ \Carbon\Carbon::parse($booking->event->time)->format('g:i A') }}
                            @else
                                TBD
                            @endif
                        @else
                            -
                        @endif
                    </dd>

                    <dt class="col-sm-4">Ticket Number</dt>
                    <dd class="col-sm-8">{{ $booking->ticket_number ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($booking->status ?? 'unknown') }}
                        </span>
                    </dd>

                    <dt class="col-sm-4">Quantity</dt>
                    <dd class="col-sm-8">{{ $booking->quantity ?? 1 }}</dd>

                    <dt class="col-sm-4">Total Amount</dt>
                    <dd class="col-sm-8">PKR {{ number_format($booking->total_amount ?? 0, 2) }}</dd>

                    @if($booking->selected_seats && is_array($booking->selected_seats) && count($booking->selected_seats) > 0)
                        <dt class="col-sm-4">Selected Seats</dt>
                        <dd class="col-sm-8">
                            @php
                                $seats = $booking->seats();
                            @endphp
                            @if($seats && $seats->count() > 0)
                                @foreach($seats as $seat)
                                    <span class="badge bg-primary me-1 mb-1">
                                        {{ ucfirst($seat->section ?? 'Unknown') }} - {{ $seat->seat_number ?? 'N/A' }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">Seat details not available</span>
                            @endif
                        </dd>
                    @else
                        <dt class="col-sm-4">Seating</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-secondary">General Admission</span>
                        </dd>
                    @endif
                </dl>
                <hr>
                <div class="qr-code text-center">
                    @if(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
                        @php
                            try {
                                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($booking->getQrCodeString());
                                echo $qrCode;
                            } catch(Exception $e) {
                                echo '<div class="alert alert-warning"><strong>QR Code Error:</strong> Unable to generate QR code.</div>';
                            }
                        @endphp
                    @else
                        <div class="alert alert-warning">
                            <strong>QR Code Package Missing:</strong> Please install <code>simplesoftwareio/simple-qrcode</code> package.
                        </div>
                    @endif
                </div>
                
                <div class="mt-3 text-center">
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to My Bookings
                    </a>
                    @if($booking->status === 'confirmed')
                        <a href="{{ route('bookings.cancel', $booking) }}" class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to cancel this booking?')">
                            <i class="fas fa-times me-2"></i>Cancel Booking
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 