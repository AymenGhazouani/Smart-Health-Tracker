<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\SleepSession;
use App\Models\User;
use App\Models\Weight;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HealthMetricsSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Weights last 14 days
        for ($i = 14; $i >= 0; $i--) {
            Weight::create([
                'user_id' => $user->id,
                'value_kg' => 70 + (14 - $i) * 0.2,
                'measured_at' => Carbon::now()->subDays($i)->setTime(7, 30),
                'note' => $i === 0 ? 'today' : null,
            ]);
        }

        // Sleep sessions last 7 days
        for ($i = 7; $i >= 1; $i--) {
            $start = Carbon::now()->subDays($i)->setTime(23, 0);
            $end = (clone $start)->addHours(7)->addMinutes(rand(0, 90));
            SleepSession::create([
                'user_id' => $user->id,
                'started_at' => $start,
                'ended_at' => $end,
                'duration_minutes' => $start->diffInMinutes($end),
                'quality' => rand(6, 9),
                'note' => null,
            ]);
        }

        // Activities sample
        $activities = [
            ['type' => 'run', 'duration' => 35, 'dist' => 6.2, 'cal' => 420],
            ['type' => 'walk', 'duration' => 45, 'dist' => 3.0, 'cal' => 180],
            ['type' => 'cycle', 'duration' => 60, 'dist' => 20.5, 'cal' => 600],
        ];
        foreach ($activities as $idx => $a) {
            Activity::create([
                'user_id' => $user->id,
                'type' => $a['type'],
                'duration_minutes' => $a['duration'],
                'calories' => $a['cal'],
                'distance_km_times100' => (int) round($a['dist'] * 100),
                'performed_at' => Carbon::now()->subDays($idx + 1)->setTime(7 + $idx, 0),
                'note' => null,
            ]);
        }
    }
}


