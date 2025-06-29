<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Seat;

class GenerateEventSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-event-seats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sections = [
            'vip' => [
                'price' => 5000,
                'rows' => ['VIP1', 'VIP2', 'VIP3'],
                'seats_per_row' => 8,
                'description' => 'Premium front row seats with best view'
            ],
            'main' => [
                'price' => 3000,
                'rows' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'],
                'seats_per_row' => 12,
                'description' => 'Main floor seating'
            ],
            'balcony' => [
                'price' => 2000,
                'rows' => ['BA', 'BB', 'BC', 'BD'],
                'seats_per_row' => 10,
                'description' => 'Elevated balcony seating'
            ],
        ];

        $events = Event::all();
        $created = 0;

        foreach ($events as $event) {
            if ($event->seats()->count() > 0) {
                $this->info("Event '{$event->title}' already has seats. Skipping.");
                continue;
            }
            
            foreach ($sections as $section => $config) {
                foreach ($config['rows'] as $row) {
                    for ($i = 1; $i <= $config['seats_per_row']; $i++) {
                        $seatNumber = $row . $i;
                        Seat::create([
                            'event_id' => $event->id,
                            'seat_number' => $seatNumber,
                            'row' => $row,
                            'section' => $section,
                            'status' => 'available',
                            'price' => $config['price'],
                        ]);
                        $created++;
                    }
                }
            }
            $this->info("Generated seats for event: {$event->title}");
        }
        $this->info("Total seats created: $created");
    }
}
