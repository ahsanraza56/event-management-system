<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please create admin user first.');
            return;
        }

        $events = [
            [
                'title' => 'Tech Conference 2025',
                'description' => 'Annual technology conference featuring the latest innovations in software development, AI, and cloud computing. Join industry experts for networking and learning opportunities.',
                'date' => '2025-12-15',
                'time' => '09:00:00',
                'venue' => 'Convention Center, Downtown',
                'capacity' => 500,
                'price' => 15000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Holiday Music Festival',
                'description' => 'A festive three-day music festival featuring local and international artists. Food trucks, art installations, and family-friendly activities included.',
                'date' => '2025-12-20',
                'time' => '18:00:00',
                'venue' => 'Central Park',
                'capacity' => 1000,
                'price' => 5000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Year-End Business Networking',
                'description' => 'Annual networking event for professionals to close the year. Includes keynote speaker, refreshments, and structured networking sessions.',
                'date' => '2025-12-10',
                'time' => '19:00:00',
                'venue' => 'Business Center, Midtown',
                'capacity' => 200,
                'price' => 3000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Winter Art Exhibition',
                'description' => 'Opening night of contemporary winter art exhibition featuring works from emerging artists. Wine and cheese reception included.',
                'date' => '2025-12-05',
                'time' => '20:00:00',
                'venue' => 'Modern Art Gallery',
                'capacity' => 150,
                'price' => 1500.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Digital Marketing Workshop',
                'description' => 'Hands-on workshop covering SEO, social media marketing, and content strategy. Includes take-home materials and certificate.',
                'date' => '2025-12-12',
                'time' => '10:00:00',
                'venue' => 'Learning Center',
                'capacity' => 50,
                'price' => 12000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Startup Pitch Competition 2025',
                'description' => 'Annual startup pitch competition where entrepreneurs present their ideas to investors. Cash prizes and mentorship opportunities available.',
                'date' => '2025-12-18',
                'time' => '14:00:00',
                'venue' => 'Innovation Hub',
                'capacity' => 300,
                'price' => 5000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Holiday Cooking Masterclass',
                'description' => 'Learn to cook festive holiday dishes with renowned chef Marco Rossi. All ingredients and equipment provided.',
                'date' => '2025-12-08',
                'time' => '16:00:00',
                'venue' => 'Culinary Institute',
                'capacity' => 30,
                'price' => 8000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'New Year Fitness Bootcamp',
                'description' => 'High-intensity fitness bootcamp to prepare for the new year. Includes strength training, cardio, and flexibility exercises.',
                'date' => '2025-12-28',
                'time' => '07:00:00',
                'venue' => 'Community Sports Center',
                'capacity' => 100,
                'price' => 2500.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Photography Workshop',
                'description' => 'Learn photography fundamentals and advanced techniques. Bring your own camera or rent one on-site.',
                'date' => '2025-12-22',
                'time' => '13:00:00',
                'venue' => 'Creative Studio',
                'capacity' => 25,
                'price' => 7000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Book Launch: "Future of AI"',
                'description' => 'Book launch and signing event for the bestselling author Dr. Sarah Chen. Discussion and Q&A session included.',
                'date' => '2025-12-03',
                'time' => '19:30:00',
                'venue' => 'City Library Auditorium',
                'capacity' => 200,
                'price' => 1000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
            ],
            // Additional events with images
            [
                'title' => 'Comedy Night Special',
                'description' => 'An evening of laughter with top comedians from around the country. Perfect for date night or group outings.',
                'date' => '2025-12-14',
                'time' => '20:00:00',
                'venue' => 'Comedy Club Downtown',
                'capacity' => 120,
                'price' => 2000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Wine Tasting Experience',
                'description' => 'Explore premium wines from around the world with expert sommeliers. Includes cheese pairing and educational session.',
                'date' => '2025-12-07',
                'time' => '18:30:00',
                'venue' => 'Vintage Wine Cellar',
                'capacity' => 40,
                'price' => 4500.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Yoga & Meditation Retreat',
                'description' => 'A peaceful day of yoga, meditation, and mindfulness practices. Perfect for stress relief and inner peace.',
                'date' => '2025-12-21',
                'time' => '09:00:00',
                'venue' => 'Serenity Wellness Center',
                'capacity' => 60,
                'price' => 3500.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Gaming Tournament 2025',
                'description' => 'Competitive gaming tournament featuring popular titles. Prizes for winners and casual gaming areas available.',
                'date' => '2025-12-13',
                'time' => '12:00:00',
                'venue' => 'Gaming Arena',
                'capacity' => 200,
                'price' => 1500.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&h=600&fit=crop',
            ],
            [
                'title' => 'Dance Performance: "Winter Dreams"',
                'description' => 'A mesmerizing contemporary dance performance celebrating the beauty of winter. Live music accompaniment.',
                'date' => '2025-12-16',
                'time' => '19:00:00',
                'venue' => 'City Theater',
                'capacity' => 300,
                'price' => 4000.00,
                'status' => 'active',
                'image' => 'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?w=800&h=600&fit=crop',
            ],
        ];

        foreach ($events as $eventData) {
            Event::create(array_merge($eventData, ['user_id' => $admin->id]));
        }

        $this->command->info('Events seeded successfully with images!');
    }
}
