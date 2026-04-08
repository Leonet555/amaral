<?php

namespace Database\Seeders;

use App\Models\AthleteProfile;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@example.com'],
            [
                'name' => 'Organizer User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ORGANIZER,
            ]
        );

        $athlete = User::firstOrCreate(
            ['email' => 'athlete@example.com'],
            [
                'name' => 'Athlete User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ATHLETE,
            ]
        );

        AthleteProfile::firstOrCreate(
            ['user_id' => $athlete->id],
            [
                'birth_date' => '2000-01-10',
                'weight' => 76.50,
                'belt' => 'BLUE',
                'academy' => 'Sample Academy',
                'gender' => 'MALE',
            ]
        );

        $event = Event::firstOrCreate(
            ['name' => 'Open Combat Championship'],
            [
                'organizer_id' => $organizer->id,
                'description' => 'Sample seeded event.',
                'date' => now()->addMonth()->toDateString(),
                'location' => 'Recife',
                'sport_type' => 'BJJ',
                'registration_deadline' => now()->addWeeks(3),
                'status' => Event::STATUS_OPEN,
            ]
        );

        Category::firstOrCreate(
            ['event_id' => $event->id, 'belt' => 'BLUE', 'gender' => 'MALE'],
            [
                'weight_min' => 70,
                'weight_max' => 82,
                'age_min' => 18,
                'age_max' => 35,
                'max_participants' => 16,
                'bracket_generated' => false,
            ]
        );
    }
}
