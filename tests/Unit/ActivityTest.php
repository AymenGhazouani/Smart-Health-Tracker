<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_can_be_created()
    {
        $user = User::factory()->create();
        
        $activity = Activity::create([
            'user_id' => $user->id,
            'type' => 'running',
            'duration_minutes' => 30,
            'calories' => 300,
            'distance_km_times100' => 5000, // 5.00 km
            'performed_at' => now(),
            'note' => 'Morning run',
        ]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'type' => 'running',
            'duration_minutes' => 30,
            'calories' => 300,
            'distance_km_times100' => 5000,
            'note' => 'Morning run',
        ]);
    }

    public function test_activity_belongs_to_user()
    {
        $user = User::factory()->create();
        $activity = Activity::create([
            'user_id' => $user->id,
            'type' => 'running',
            'duration_minutes' => 30,
            'performed_at' => now(),
        ]);

        $this->assertInstanceOf(User::class, $activity->user);
        $this->assertEquals($user->id, $activity->user->id);
    }

    public function test_activity_performed_at_is_cast_to_datetime()
    {
        $user = User::factory()->create();
        $activity = Activity::create([
            'user_id' => $user->id,
            'type' => 'running',
            'duration_minutes' => 30,
            'performed_at' => now(),
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $activity->performed_at);
    }

    public function test_activity_can_be_created_with_minimal_data()
    {
        $user = User::factory()->create();
        
        $activity = Activity::create([
            'user_id' => $user->id,
            'type' => 'walking',
            'duration_minutes' => 15,
            'performed_at' => now(),
        ]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'type' => 'walking',
            'duration_minutes' => 15,
            'calories' => null,
            'distance_km_times100' => null,
            'note' => null,
        ]);
    }

    public function test_activity_distance_calculation()
    {
        $user = User::factory()->create();
        $activity = Activity::create([
            'user_id' => $user->id,
            'type' => 'running',
            'duration_minutes' => 30,
            'distance_km_times100' => 5000, // 5.00 km
            'performed_at' => now(),
        ]);

        // Test that the distance is stored correctly
        $this->assertEquals(5000, $activity->distance_km_times100);
    }
}
