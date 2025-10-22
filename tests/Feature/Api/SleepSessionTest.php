<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\SleepSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SleepSessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_get_sleep_sessions()
    {
        SleepSession::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/sleep-sessions');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_create_sleep_session()
    {
        $sleepData = [
            'started_at' => now()->subHours(8)->toISOString(),
            'ended_at' => now()->toISOString(),
            'duration_minutes' => 480,
            'quality' => 'good',
            'note' => 'Slept well',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/sleep-sessions', $sleepData);

        $response->assertStatus(201)
                ->assertJson([
                    'duration_minutes' => 480,
                    'quality' => 'good',
                    'note' => 'Slept well',
                ]);

        $this->assertDatabaseHas('sleep_sessions', [
            'user_id' => $this->user->id,
            'duration_minutes' => 480,
            'quality' => 'good',
        ]);
    }

    public function test_authenticated_user_can_get_specific_sleep_session()
    {
        $sleepSession = SleepSession::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/sleep-sessions/{$sleepSession->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $sleepSession->id,
                    'duration_minutes' => $sleepSession->duration_minutes,
                ]);
    }

    public function test_authenticated_user_can_update_sleep_session()
    {
        $sleepSession = SleepSession::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'quality' => 'excellent',
            'note' => 'Updated sleep quality',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/sleep-sessions/{$sleepSession->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'quality' => 'excellent',
                    'note' => 'Updated sleep quality',
                ]);

        $this->assertDatabaseHas('sleep_sessions', [
            'id' => $sleepSession->id,
            'quality' => 'excellent',
            'note' => 'Updated sleep quality',
        ]);
    }

    public function test_authenticated_user_can_delete_sleep_session()
    {
        $sleepSession = SleepSession::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/sleep-sessions/{$sleepSession->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('sleep_sessions', [
            'id' => $sleepSession->id,
        ]);
    }

    public function test_user_cannot_access_other_users_sleep_sessions()
    {
        $otherUser = User::factory()->create();
        $sleepSession = SleepSession::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/sleep-sessions/{$sleepSession->id}");

        $response->assertStatus(404);
    }

    public function test_sleep_session_creation_validation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/sleep-sessions', [
            'started_at' => 'invalid-date',
            'ended_at' => 'invalid-date',
            'duration_minutes' => 'invalid',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['started_at', 'ended_at', 'duration_minutes']);
    }

    public function test_sleep_session_can_be_created_with_minimal_data()
    {
        $sleepData = [
            'started_at' => now()->subHours(8)->toISOString(),
            'ended_at' => now()->toISOString(),
            'duration_minutes' => 480,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/sleep-sessions', $sleepData);

        $response->assertStatus(201)
                ->assertJson([
                    'duration_minutes' => 480,
                    'quality' => null,
                    'note' => null,
                ]);
    }

    public function test_unauthenticated_user_cannot_access_sleep_sessions()
    {
        $response = $this->getJson('/api/v1/sleep-sessions');

        $response->assertStatus(401);
    }
}
