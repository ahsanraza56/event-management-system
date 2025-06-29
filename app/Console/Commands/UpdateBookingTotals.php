<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class UpdateBookingTotals extends Command
{
    protected $signature = 'app:update-booking-totals';
    protected $description = 'Update existing bookings with total_amount values';

    public function handle()
    {
        $this->info('Updating booking totals...');
        
        $bookings = Booking::all();
        $updated = 0;
        
        foreach ($bookings as $booking) {
            $totalAmount = $booking->getTotalAmountAttribute();
            
            if ($booking->total_amount != $totalAmount) {
                $booking->update(['total_amount' => $totalAmount]);
                $updated++;
                $this->line("Updated booking {$booking->ticket_number}: PKR {$totalAmount}");
            }
        }
        
        $this->info("Updated {$updated} bookings with total amounts.");
        
        return 0;
    }
} 