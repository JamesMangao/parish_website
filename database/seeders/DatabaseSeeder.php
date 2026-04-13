<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@storosario.ph'],
            [
                'name' => 'Sto. Rosario Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // 2. Sample Mass Schedules
        $schedules = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => 'Sunday Mass',
                'mass_type' => 'sunday',
                'day_of_week' => ['Sunday'],
                'time' => ['06:30', '08:30', '10:00', '15:00', '16:30', '18:00'],
                'location' => 'Main Church',
                'is_active' => true,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => 'Anticipated Sunday Mass',
                'mass_type' => 'anticipated',
                'day_of_week' => ['Saturday'],
                'time' => ['18:00'],
                'location' => 'Main Church',
                'is_active' => true,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => 'Weekday Mass',
                'mass_type' => 'weekday',
                'day_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'time' => ['06:30'],
                'location' => 'Chapel',
                'is_active' => true,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => 'Saturday Morning Mass',
                'mass_type' => 'saturday',
                'day_of_week' => ['Saturday'],
                'time' => ['06:30'],
                'location' => 'Chapel',
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $s) {
            \App\Models\MassSchedule::updateOrCreate(['title' => $s['title']], $s);
        }

        // 3. Sample Announcements
        \App\Models\Announcement::updateOrCreate(
            ['title' => 'Welcome to our new website!'],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'content' => 'We are happy to announce the launch of our new parish management system. You can now submit mass intentions online!',
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );

        // 4. Sample Events
        \App\Models\Event::updateOrCreate(
            ['title' => 'Parish Fiesta 2026'],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'description' => 'Join us for our annual parish fiesta celebrations.',
                'event_date' => now()->addMonths(2),
                'event_time' => [['time' => '09:00:00', 'title' => 'Main Mass']],
                'location' => 'Parish Grounds',
                'is_published' => true,
            ]
        );

        // 5. Sample Staff User
        User::updateOrCreate(
            ['email' => 'staff@storosario.ph'],
            [
                'name' => 'Parish Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // 6. Sample SocCom User
        User::updateOrCreate(
            ['email' => 'soccom@storosario.ph'],
            [
                'name' => 'SocCom Team',
                'password' => Hash::make('password'),
                'role' => 'soccom',
            ]
        );

        // 7. Sample Inquiries
        $inquiries = [
            [
                'full_name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '09171234567',
                'inquiry_type' => 'Baptism',
                'message' => 'I would like to inquire about requirements for baptism next month.',
                'status' => 'pending',
                'created_at' => now()->subDays(2),
            ],
            [
                'full_name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'phone' => '09187654321',
                'inquiry_type' => 'Wedding',
                'message' => 'Planning a wedding in December 2026. Is the chapel available?',
                'status' => 'accepted',
                'accepted_at' => now()->subDay(),
                'created_at' => now()->subDays(5),
            ],
            [
                'full_name' => 'Pedro Penduko',
                'email' => 'pedro.p@example.com',
                'phone' => '09223334444',
                'inquiry_type' => 'Car Blessing',
                'message' => 'Requesting a car blessing this Saturday after the morning mass.',
                'status' => 'pending',
                'created_at' => now()->subHours(5),
            ],
        ];

        foreach ($inquiries as $i) {
            \App\Models\Inquiry::create($i);
        }

        // 8. Sample Chat Sessions
        $chat = \App\Models\ChatSession::create([
            'session_id' => \Illuminate\Support\Str::random(40),
            'user_ip' => '127.0.0.1',
            'status' => 'handover', // Waiting for agent
            'live_agent_requested_at' => now()->subMinutes(10),
        ]);

        \App\Models\ChatMessage::create([
            'chat_session_id' => $chat->id,
            'sender' => 'user',
            'message' => 'Hello, is there anyone I can talk to about a lost confirmation certificate?',
        ]);

        \App\Models\ChatMessage::create([
            'chat_session_id' => $chat->id,
            'sender' => 'ai',
            'message' => "I understand you'd like to speak with a person. Would you like to submit a formal Inquiry or wait for a Live Agent to connect?",
        ]);

        \App\Models\ChatMessage::create([
            'chat_session_id' => $chat->id,
            'sender' => 'user',
            'message' => 'I will wait for a live agent.',
        ]);
    }
}
