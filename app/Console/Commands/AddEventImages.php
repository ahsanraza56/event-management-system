<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
    protected $description = 'Add sample images to events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding sample images to events...');

        $events = Event::whereNull('image')->get();

        if ($events->isEmpty()) {
            $this->info('All events already have images!');
            return 0;
        }

        $imageUrls = [
            'tech' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&h=600&fit=crop',
            'music' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&h=600&fit=crop',
            'business' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
            'art' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=800&h=600&fit=crop',
            'workshop' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
            'startup' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&h=600&fit=crop',
            'cooking' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop',
            'fitness' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
            'photography' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&h=600&fit=crop',
            'book' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
        ];

        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        foreach ($events as $event) {
            $imageType = $this->getImageType($event->title);
            $imageUrl = $imageUrls[$imageType] ?? $imageUrls['business'];

            try {
                // Download image
                $response = Http::timeout(30)->get($imageUrl);
                
                if ($response->successful()) {
                    $imageContent = $response->body();
                    $filename = 'events/' . uniqid() . '.jpg';
                    
                    // Store image
                    Storage::disk('public')->put($filename, $imageContent);
                    
                    // Update event
                    $event->update(['image' => $filename]);
                    
                    $this->line(" ✓ Added image to: {$event->title}");
                } else {
                    $this->line(" ✗ Failed to download image for: {$event->title}");
                }
            } catch (\Exception $e) {
                $this->line(" ✗ Error adding image to {$event->title}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Event images added successfully!');

        return 0;
    }

    /**
     * Determine image type based on event title
     */
    private function getImageType($title)
    {
        $title = strtolower($title);
        
        if (str_contains($title, 'tech') || str_contains($title, 'conference')) {
            return 'tech';
        }
        if (str_contains($title, 'music') || str_contains($title, 'festival')) {
            return 'music';
        }
        if (str_contains($title, 'business') || str_contains($title, 'networking')) {
            return 'business';
        }
        if (str_contains($title, 'art') || str_contains($title, 'exhibition')) {
            return 'art';
        }
        if (str_contains($title, 'workshop') || str_contains($title, 'digital marketing')) {
            return 'workshop';
        }
        if (str_contains($title, 'startup') || str_contains($title, 'pitch')) {
            return 'startup';
        }
        if (str_contains($title, 'cooking') || str_contains($title, 'culinary')) {
            return 'cooking';
        }
        if (str_contains($title, 'fitness') || str_contains($title, 'bootcamp')) {
            return 'fitness';
        }
        if (str_contains($title, 'photography')) {
            return 'photography';
        }
        if (str_contains($title, 'book') || str_contains($title, 'launch')) {
            return 'book';
        }
        
        return 'business'; // default
    }
}
