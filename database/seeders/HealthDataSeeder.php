<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Weight;
use App\Models\Activity;
use App\Models\SleepSession;
use Carbon\Carbon;

class HealthDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to get the admin user first, then any user
        $user = User::where('email', 'admin@admin.com')->first() 
               ?? User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info("Seeding health data for user: {$user->name}");

        // Clear existing data for this user
        Weight::where('user_id', $user->id)->delete();
        Activity::where('user_id', $user->id)->delete();
        SleepSession::where('user_id', $user->id)->delete();

        // Seed Weight Data (90 days)
        $this->seedWeightData($user);
        
        // Seed Activity Data (60 days)
        $this->seedActivityData($user);
        
        // Seed Sleep Data (45 days)
        $this->seedSleepData($user);

        $this->command->info('Health data seeding completed successfully!');
    }

    private function seedWeightData(User $user): void
    {
        $this->command->info('Seeding weight data...');
        
        $baseWeight = 70.0; // Starting weight in kg
        $currentWeight = $baseWeight;
        
        for ($i = 90; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Create realistic weight fluctuations
            $dailyChange = $this->getWeightChange($i, 90);
            $currentWeight += $dailyChange;
            
            // Add some random daily variation (±0.5kg)
            $variation = (rand(-50, 50) / 100);
            $recordedWeight = $currentWeight + $variation;
            
            // Ensure weight stays within reasonable bounds
            $recordedWeight = max(50, min(100, $recordedWeight));
            
            Weight::create([
                'user_id' => $user->id,
                'value_kg' => round($recordedWeight, 2),
                'measured_at' => $date->setTime(7, rand(0, 30)), // Morning weigh-ins
                'note' => $this->getWeightNote($i),
            ]);
        }
    }

    private function seedActivityData(User $user): void
    {
        $this->command->info('Seeding activity data...');
        
        $activityTypes = [
            'running' => ['min_duration' => 20, 'max_duration' => 60, 'calories_per_min' => 12],
            'cycling' => ['min_duration' => 30, 'max_duration' => 90, 'calories_per_min' => 8],
            'swimming' => ['min_duration' => 30, 'max_duration' => 60, 'calories_per_min' => 10],
            'walking' => ['min_duration' => 15, 'max_duration' => 120, 'calories_per_min' => 4],
            'gym' => ['min_duration' => 45, 'max_duration' => 90, 'calories_per_min' => 7],
            'yoga' => ['min_duration' => 30, 'max_duration' => 90, 'calories_per_min' => 3],
            'dancing' => ['min_duration' => 30, 'max_duration' => 120, 'calories_per_min' => 6],
            'hiking' => ['min_duration' => 60, 'max_duration' => 240, 'calories_per_min' => 6],
            'sports' => ['min_duration' => 30, 'max_duration' => 120, 'calories_per_min' => 9],
        ];

        for ($i = 60; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip some days (not everyone exercises daily)
            if (rand(1, 100) <= 25) continue; // 25% chance to skip
            
            // Some days have multiple activities
            $activitiesCount = rand(1, 100) <= 15 ? 2 : 1; // 15% chance for 2 activities
            
            for ($j = 0; $j < $activitiesCount; $j++) {
                $activityType = array_rand($activityTypes);
                $activityData = $activityTypes[$activityType];
                
                $duration = rand($activityData['min_duration'], $activityData['max_duration']);
                $calories = $duration * $activityData['calories_per_min'] + rand(-50, 50);
                
                // Calculate distance for relevant activities
                $distance = null;
                if (in_array($activityType, ['running', 'cycling', 'walking', 'hiking'])) {
                    $speedFactor = [
                        'running' => rand(8, 15), // km/h
                        'cycling' => rand(15, 30),
                        'walking' => rand(4, 7),
                        'hiking' => rand(3, 6)
                    ];
                    $distance = ($duration / 60) * $speedFactor[$activityType] * 100; // times 100 as per model
                }
                
                Activity::create([
                    'user_id' => $user->id,
                    'type' => $activityType,
                    'duration_minutes' => $duration,
                    'calories' => max(50, $calories),
                    'distance_km_times100' => $distance ? round($distance) : null,
                    'performed_at' => $date->copy()->setTime(rand(6, 20), rand(0, 59)),
                    'note' => $this->getActivityNote($activityType, $duration),
                ]);
            }
        }
    }

    private function seedSleepData(User $user): void
    {
        $this->command->info('Seeding sleep data...');
        
        for ($i = 45; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip occasional nights (travel, etc.)
            if (rand(1, 100) <= 5) continue; // 5% chance to skip
            
            // Generate realistic sleep patterns
            $bedtime = $this->getBedtime($date);
            $sleepDuration = $this->getSleepDuration($date);
            $wakeTime = $bedtime->copy()->addMinutes($sleepDuration);
            
            // Sleep quality based on duration and consistency
            $quality = $this->getSleepQuality($sleepDuration, $bedtime);
            
            SleepSession::create([
                'user_id' => $user->id,
                'started_at' => $bedtime,
                'ended_at' => $wakeTime,
                'duration_minutes' => $sleepDuration,
                'quality' => $quality,
                'note' => $this->getSleepNote($quality, $sleepDuration),
            ]);
        }
    }

    private function getWeightChange(int $dayIndex, int $totalDays): float
    {
        // Create a realistic weight loss/gain pattern
        $progress = $dayIndex / $totalDays;
        
        // Simulate a weight loss journey with plateaus
        if ($progress < 0.3) {
            return rand(-15, 5) / 100; // Initial loss
        } elseif ($progress < 0.6) {
            return rand(-5, 5) / 100; // Plateau
        } elseif ($progress < 0.8) {
            return rand(-10, 3) / 100; // More loss
        } else {
            return rand(-3, 8) / 100; // Recent slight gain
        }
    }

    private function getWeightNote(int $dayIndex): ?string
    {
        $notes = [
            'Morning weigh-in',
            'After workout',
            'Feeling good today',
            'Had a big meal yesterday',
            'Stayed hydrated',
            null, null, null // Most entries have no note
        ];
        
        return $notes[array_rand($notes)];
    }

    private function getBedtime(Carbon $date): Carbon
    {
        // Weekend vs weekday bedtimes
        $isWeekend = $date->isWeekend();
        
        if ($isWeekend) {
            $hour = rand(22, 25); // 10 PM to 1 AM
            $minute = rand(0, 59);
        } else {
            $hour = rand(21, 24); // 9 PM to 12 AM
            $minute = rand(0, 59);
        }
        
        // Handle hours > 23
        if ($hour >= 24) {
            $hour -= 24;
            $date = $date->copy()->addDay();
        }
        
        return $date->copy()->setTime($hour, $minute);
    }

    private function getSleepDuration(Carbon $date): int
    {
        // Realistic sleep duration with some variation
        $isWeekend = $date->isWeekend();
        
        if ($isWeekend) {
            return rand(420, 540); // 7-9 hours
        } else {
            return rand(360, 480); // 6-8 hours
        }
    }

    private function getSleepQuality(int $duration, Carbon $bedtime): int
    {
        $quality = 3; // Base quality
        
        // Better quality for optimal duration (7-8 hours)
        if ($duration >= 420 && $duration <= 480) {
            $quality += 1;
        }
        
        // Better quality for consistent bedtime (before 11 PM)
        if ($bedtime->hour <= 23) {
            $quality += 1;
        }
        
        // Random variation
        $quality += rand(-1, 1);
        
        return max(1, min(5, $quality));
    }

    private function getSleepNote(int $quality, int $duration): ?string
    {
        if ($quality >= 4) {
            $notes = ['Slept well', 'Refreshed morning', 'Good night\'s sleep', null];
        } elseif ($quality <= 2) {
            $notes = ['Restless night', 'Woke up tired', 'Had trouble falling asleep', 'Interrupted sleep'];
        } else {
            $notes = ['Average sleep', 'Okay night', null, null];
        }
        
        return $notes[array_rand($notes)];
    }

    private function getActivityNote(string $type, int $duration): ?string
    {
        $notes = [
            'running' => ['Great run!', 'Morning jog', 'Felt strong today', 'Good pace'],
            'cycling' => ['Nice ride', 'Explored new route', 'Good workout', 'Enjoyed the weather'],
            'gym' => ['Strength training', 'Full body workout', 'Good session', 'Hit new PR'],
            'yoga' => ['Relaxing session', 'Morning flow', 'Flexibility work', 'Mindful practice'],
            'swimming' => ['Pool workout', 'Good laps', 'Technique practice', 'Refreshing swim'],
        ];
        
        $typeNotes = $notes[$type] ?? ['Good workout', 'Felt great', 'Enjoyed it'];
        $typeNotes[] = null; // Add chance for no note
        
        return $typeNotes[array_rand($typeNotes)];
    }
}