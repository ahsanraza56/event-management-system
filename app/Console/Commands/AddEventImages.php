<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class AddEventImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:add-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add images to events that don\'t have images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding images to events...');

        $events = Event::whereNull('image')->orWhere('image', '')->get();
        
        if ($events->isEmpty()) {
            $this->info('All events already have images!');
            return;
        }

        $imageUrls = [
            'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop', // Tech
            'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&h=600&fit=crop', // Music
            'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&h=600&fit=crop', // Business
            'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=800&h=600&fit=crop', // Art
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop', // Workshop
            'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&h=600&fit=crop', // Startup
            'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop', // Cooking
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop', // Fitness
            'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&h=600&fit=crop', // Photography
            'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop', // Books
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop', // Comedy
            'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=800&h=600&fit=crop', // Wine
            'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=600&fit=crop', // Yoga
            'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=600&fit=crop', // Gaming
            'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?w=800&h=600&fit=crop', // Dance
        ];

        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        foreach ($events as $index => $event) {
            $imageIndex = $index % count($imageUrls);
            $event->update(['image' => $imageUrls[$imageIndex]]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully added images to ' . $events->count() . ' events!');
    }
}
