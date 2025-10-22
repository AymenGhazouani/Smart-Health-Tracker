<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Weight;
use App\Models\Activity;
use App\Models\SleepSession;
use Carbon\Carbon;

class SeedHealthDataForAdmin extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'health:seed-admin {email=admin@admin.com}';

    /**
     * The console command description.
     */
    protected $description = 'Seed health data for admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return Command::FAILURE;
        }

        $this->info("🏥 Seeding health data for: {$user->name} ({$user->email})");
        
        // Clear existing data
        $this->info('Clearing existing data...');
        Weight::where('user_id', $user->id)->delete();
        Activity::where('user_id', $user->id)->delete();
        SleepSession::where('user_id', $user->id)->delete();

        // Seed new data
        $this->info('Generating weight data (90 days)...');
        $this->seedWeightData($user);
        
        $this->info('Generating activity data (60 days)...');
        $this->seedActivityData($user);
        
        $this->info('Generating sleep data (45 days)...');
        $this->seedSleepData($user);

        $this->info('✅ Health data seeding completed!');
        $this->info('📊 Data generated:');
        $this->info('   • ' . Weight::where('user_id', $user->id)->count() . ' weight measurements');
        $this->info('   • ' . Activity::where('user_id', $user->id)->count() . ' activity records');
        $this->info('   • ' . SleepSession::where('user_id', $user->id)->count() . ' sleep sessions');
        $this->info('');
        $this->info('🚀 You can now test the AI features:');
        $this->info('   • Health AI Dashboard: /health-ai');
        $this->info('   • Health Predictions: /health-predictions');
        $this->info('   • Machine Learning: /health-ml');
        
        return Command::SUCCESS;
    }

    private function seedWeightData($user): void
    {
        $baseWeight = 75.0; // Starting weight in kg
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
                'measured_at' => $date->setTime(7, rand(0, 30)),
                'note' => rand(1, 4) == 1 ? 'Morning weigh-in' : null,
            ]);
        }
    }

    private function seedActivityData($user): void
    {
        $activityTypes = [
            'running' => ['min_duration' => 20, 'max_duration' => 60, 'calories_per_min' => 12],
            'cycling' => ['min_duration' => 30, 'max_duration' => 90, 'calories_per_min' => 8],
            'swimming' => ['min_duration' => 30, 'max_duration' => 60, 'calories_per_min' => 10],
            'walking' => ['min_duration' => 15, 'max_duration' => 120, 'calories_per_min' => 4],
            'gym' => ['min_duration' => 45, 'max_duration' => 90, 'calories_per_min' => 7],
            'yoga' => ['min_duration' => 30, 'max_duration' => 90, 'calories_per_min' => 3],
        ];

        for ($i = 60; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip some days (not everyone exercises daily)
            if (rand(1, 100) <= 30) continue; // 30% chance to skip
            
            $activityType = array_rand($activityTypes);
            $activityData = $activityTypes[$activityType];
            
            $duration = rand($activityData['min_duration'], $activityData['max_duration']);
            $calories = $duration * $activityData['calories_per_min'] + rand(-50, 50);
            
            Activity::create([
                'user_id' => $user->id,
                'type' => $activityType,
                'duration_minutes' => $duration,
                'calories' => max(50, $calories),
                'distance_km_times100' => in_array($activityType, ['running', 'cycling', 'walking']) 
                    ? rand(300, 1500) : null,
                'performed_at' => $date->copy()->setTime(rand(6, 20), rand(0, 59)),
                'note' => rand(1, 3) == 1 ? 'Good workout' : null,
            ]);
        }
    }

    private function seedSleepData($user): void
    {
        for ($i = 45; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Skip occasional nights
            if (rand(1, 100) <= 5) continue; // 5% chance to skip
            
            $bedtimeHour = rand(21, 23);
            $bedtimeMinute = rand(0, 59);
            $bedtime = $date->copy()->setTime($bedtimeHour, $bedtimeMinute);
            
            $sleepDuration = rand(360, 540); // 6-9 hours
            $wakeTime = $bedtime->copy()->addMinutes($sleepDuration);
            
            $quality = rand(2, 5); // Sleep quality 2-5
            
            SleepSession::create([
                'user_id' => $user->id,
                'started_at' => $bedtime,
                'ended_at' => $wakeTime,
                'duration_minutes' => $sleepDuration,
                'quality' => $quality,
                'note' => $quality >= 4 ? 'Good sleep' : ($quality <= 2 ? 'Restless night' : null),
            ]);
        }
    }

    private function getWeightChange(int $dayIndex, int $totalDays): float
    {
        $progress = $dayIndex / $totalDays;
        
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
}