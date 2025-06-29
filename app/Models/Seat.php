<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'seat_number',
        'row',
        'section',
        'status',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the event that owns the seat
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if seat is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if seat is booked
     */
    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }

    /**
     * Check if seat is reserved
     */
    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    /**
     * Get the effective price (seat price or event price)
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->event->price;
    }

    /**
     * Scope to get available seats
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope to get booked seats
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope to get seats by section
     */
    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope to get seats by row
     */
    public function scopeByRow($query, $row)
    {
        return $query->where('row', $row);
    }
}
