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
            ],
        ];

        foreach ($events as $eventData) {
            Event::create(array_merge($eventData, ['user_id' => $admin->id]));
        }

        $this->command->info('Events seeded successfully!');
    }
}
