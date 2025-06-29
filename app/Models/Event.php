<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'venue',
        'capacity',
        'price',
        'image',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that created the event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get bookings for this event
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get seats for this event
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Get available seats for this event
     */
    public function availableSeats(): HasMany
    {
        return $this->hasMany(Seat::class)->available();
    }

    /**
     * Get booked seats for this event
     */
    public function bookedSeats(): HasMany
    {
        return $this->hasMany(Seat::class)->booked();
    }

    /**
     * Check if event has seat selection enabled
     */
    public function hasSeatSelection(): bool
    {
        return $this->seats()->count() > 0;
    }

    /**
     * Get available seats count
     */
    public function getAvailableSeatsAttribute(): int
    {
        if ($this->hasSeatSelection()) {
            return $this->seats()->available()->count();
        }
        return $this->capacity - $this->bookings()->count();
    }

    /**
     * Check if event is full
     */
    public function isFull(): bool
    {
        return $this->available_seats <= 0;
    }

    /**
     * Check if event is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
} 