<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_get_activities()
    {
        Activity::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/activities');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_create_activity()
    {
        $activityData = [
            'type' => 'running',
            'duration_minutes' => 30,
            'calories' => 300,
            'distance_km_times100' => 5000,
            'performed_at' => now()->toISOString(),
            'note' => 'Morning run',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/activities', $activityData);

        $response->assertStatus(201)
                ->assertJson([
                    'type' => 'running',
                    'duration_minutes' => 30,
                    'calories' => 300,
                    'distance_km_times100' => 5000,
                    'note' => 'Morning run',
                ]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $this->user->id,
            'type' => 'running',
            'duration_minutes' => 30,
        ]);
    }

    public function test_authenticated_user_can_get_specific_activity()
    {
        $activity = Activity::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/activities/{$activity->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $activity->id,
                    'type' => $activity->type,
                ]);
    }

    public function test_authenticated_user_can_update_activity()
    {
        $activity = Activity::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'type' => 'cycling',
            'duration_minutes' => 45,
            'note' => 'Updated activity',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/activities/{$activity->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'type' => 'cycling',
                    'duration_minutes' => 45,
                    'note' => 'Updated activity',
                ]);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'type' => 'cycling',
            'duration_minutes' => 45,
        ]);
    }

    public function test_authenticated_user_can_delete_activity()
    {
        $activity = Activity::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/activities/{$activity->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('activities', [
            'id' => $activity->id,
        ]);
    }

    public function test_user_cannot_access_other_users_activities()
    {
        $otherUser = User::factory()->create();
        $activity = Activity::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/activities/{$activity->id}");

        $response->assertStatus(404);
    }

    public function test_activity_creation_validation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/activities', [
            'type' => '',
            'duration_minutes' => 'invalid',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['type', 'duration_minutes']);
    }

    public function test_activity_can_be_created_with_minimal_data()
    {
        $activityData = [
            'type' => 'walking',
            'duration_minutes' => 15,
            'performed_at' => now()->toISOString(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/activities', $activityData);

        $response->assertStatus(201)
                ->assertJson([
                    'type' => 'walking',
                    'duration_minutes' => 15,
                    'calories' => null,
                    'distance_km_times100' => null,
                    'note' => null,
                ]);
    }

    public function test_unauthenticated_user_cannot_access_activities()
    {
        $response = $this->getJson('/api/v1/activities');

        $response->assertStatus(401);
    }
}
