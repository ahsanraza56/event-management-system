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
        'total_amount',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'selected_seats' => 'array',
        'total_amount' => 'decimal:2',
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
        if (!$this->selected_seats || !is_array($this->selected_seats) || empty($this->selected_seats)) {
            return collect(); // Return empty collection
        }
        
        return Seat::whereIn('id', $this->selected_seats)->get();
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
        return "Event: " . ($this->event->title ?? 'Unknown Event') . "\n"
            . "Venue: " . ($this->event->venue ?? 'TBD') . "\n"
            . "Date: " . ($this->event->date ? $this->event->date->format('Y-m-d') : 'TBD') . "\n"
            . "Time: " . ($this->event->time ? \Carbon\Carbon::parse($this->event->time)->format('h:i A') : 'TBD') . "\n"
            . "Ticket #: " . ($this->ticket_number ?? 'N/A') . "\n"
            . "Seats Booked: " . ($this->quantity ?? 1) . "\n"
            . "Name: " . ($this->user->name ?? 'Unknown') . "\n"
            . "Email: " . ($this->user->email ?? 'N/A') . "\n"
            . "Status: " . ucfirst($this->status ?? 'unknown');
    }

    /**
     * Calculate total amount for this booking (fallback method)
     */
    public function getTotalAmountAttribute(): float
    {
        // If total_amount is already set, return it
        if (isset($this->attributes['total_amount']) && $this->attributes['total_amount'] > 0) {
            return (float) $this->attributes['total_amount'];
        }

        // Fallback calculation
        if ($this->selected_seats && count($this->selected_seats) > 0) {
            // Calculate based on selected seats
            return $this->seats()->sum('price');
        } else {
            // Calculate based on event price and quantity
            return ($this->event->price ?? 0) * $this->quantity;
        }
    }
} 