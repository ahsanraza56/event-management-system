<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $oldStatus;
    public $newStatus;

    public function __construct(Booking $booking, $oldStatus, $newStatus)
    {
        $this->booking = $booking;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function envelope(): Envelope
    {
        $statusText = ucfirst($this->newStatus);
        return new Envelope(
            subject: "Booking Status Updated - {$statusText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-status-update',
        );
    }
} 