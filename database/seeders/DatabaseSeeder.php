<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('ahsan'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create sample events
        Event::create([
            'title' => 'Tech Conference 2024',
            'description' => 'Join us for the biggest tech conference of the year featuring keynote speakers, workshops, and networking opportunities.',
            'date' => now()->addDays(30),
            'time' => '09:00:00',
            'venue' => 'Convention Center',
            'capacity' => 200,
            'price' => 99.99,
            'status' => 'active',
            'user_id' => 1, // Admin user
        ]);

        Event::create([
            'title' => 'Music Festival',
            'description' => 'A three-day music festival featuring top artists from around the world.',
            'date' => now()->addDays(45),
            'time' => '18:00:00',
            'venue' => 'Central Park',
            'capacity' => 500,
            'price' => 149.99,
            'status' => 'active',
            'user_id' => 1, // Admin user
        ]);

        Event::create([
            'title' => 'Business Workshop',
            'description' => 'Learn essential business skills from industry experts in this comprehensive workshop.',
            'date' => now()->addDays(15),
            'time' => '10:00:00',
            'venue' => 'Business Center',
            'capacity' => 50,
            'price' => 75.00,
            'status' => 'active',
            'user_id' => 1, // Admin user
        ]);
    }
}
