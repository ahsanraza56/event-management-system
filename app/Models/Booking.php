<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_number',
        'qr_code',
        'status',
        'booking_date',
        'quantity',
        'selected_seats', // JSON array of selected seat IDs
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'selected_seats' => 'array',
    ];

    /**
     * Boot method to generate ticket number and QR code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->ticket_number = 'TIX-' . strtoupper(Str::random(8));
            $booking->qr_code = $booking->generateQRCode();
        });
    }

    /**
     * Get the user that made the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for this booking
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get selected seats for this booking
     */
    public function seats()
    {
        return Seat::whereIn('id', $this->selected_seats ?? []);
    }

    /**
     * Get selected seat numbers as string
     */
    public function getSelectedSeatNumbersAttribute(): string
    {
        if (!$this->selected_seats) {
            return 'General Admission';
        }
        
        $seatNumbers = $this->seats()->pluck('seat_number')->toArray();
        return implode(', ', $seatNumbers);
    }

    /**
     * Generate QR code data
     */
    private function generateQRCode(): string
    {
        return json_encode([
            'ticket_number' => $this->ticket_number,
            'user_id' => $this->user_id,
            'event_id' => $this->event_id,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Generate a human-readable QR code string for the ticket
     */
    public function getQrCodeString()
    {
        return "Event: " . ($this->event->title ?? '-') . "\n"
            . "Venue: " . ($this->event->venue ?? '-') . "\n"
            . "Date: " . ($this->event->date ? $this->event->date->format('Y-m-d') : '-') . "\n"
            . "Time: " . ($this->event->time ? \Carbon\Carbon::parse($this->event->time)->format('h:i A') : '-') . "\n"
            . "Ticket #: " . $this->ticket_number . "\n"
            . "Seats Booked: " . $this->quantity . "\n"
            . "Name: " . ($this->user->name ?? '-') . "\n"
            . "Email: " . ($this->user->email ?? '-') . "\n"
            . "Status: " . ucfirst($this->status);
    }

    /**
     * Calculate total amount for this booking
     */
    public function getTotalAmountAttribute(): float
    {
        if ($this->selected_seats && count($this->selected_seats) > 0) {
            // Calculate based on selected seats
            return $this->seats()->sum('price');
        } else {
            // Calculate based on event price and quantity
            return ($this->event->price ?? 0) * $this->quantity;
        }
    }
} 