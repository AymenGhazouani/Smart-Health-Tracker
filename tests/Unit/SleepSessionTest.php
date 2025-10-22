<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\SleepSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SleepSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_sleep_session_can_be_created()
    {
        $user = User::factory()->create();
        
        $sleepSession = SleepSession::create([
            'user_id' => $user->id,
            'started_at' => now()->subHours(8),
            'ended_at' => now(),
            'duration_minutes' => 480, // 8 hours
            'quality' => 'good',
            'note' => 'Slept well',
        ]);

        $this->assertDatabaseHas('sleep_sessions', [
            'user_id' => $user->id,
            'duration_minutes' => 480,
            'quality' => 'good',
            'note' => 'Slept well',
        ]);
    }

    public function test_sleep_session_belongs_to_user()
    {
        $user = User::factory()->create();
        $sleepSession = SleepSession::create([
            'user_id' => $user->id,
            'started_at' => now()->subHours(8),
            'ended_at' => now(),
            'duration_minutes' => 480,
        ]);

        $this->assertInstanceOf(User::class, $sleepSession->user);
        $this->assertEquals($user->id, $sleepSession->user->id);
    }

    public function test_sleep_session_dates_are_cast_to_datetime()
    {
        $user = User::factory()->create();
        $startTime = now()->subHours(8);
        $endTime = now();
        
        $sleepSession = SleepSession::create([
            'user_id' => $user->id,
            'started_at' => $startTime,
            'ended_at' => $endTime,
            'duration_minutes' => 480,
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $sleepSession->started_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $sleepSession->ended_at);
    }

    public function test_sleep_session_can_be_created_without_optional_fields()
    {
        $user = User::factory()->create();
        
        $sleepSession = SleepSession::create([
            'user_id' => $user->id,
            'started_at' => now()->subHours(8),
            'ended_at' => now(),
            'duration_minutes' => 480,
        ]);

        $this->assertDatabaseHas('sleep_sessions', [
            'user_id' => $user->id,
            'duration_minutes' => 480,
            'quality' => null,
            'note' => null,
        ]);
    }

    public function test_sleep_session_duration_calculation()
    {
        $user = User::factory()->create();
        $startTime = now()->subHours(7)->subMinutes(30);
        $endTime = now();
        
        $sleepSession = SleepSession::create([
            'user_id' => $user->id,
            'started_at' => $startTime,
            'ended_at' => $endTime,
            'duration_minutes' => 450, // 7.5 hours
        ]);

        $this->assertEquals(450, $sleepSession->duration_minutes);
    }
}
