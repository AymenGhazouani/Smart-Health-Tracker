<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Weight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeightTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_get_weights()
    {
        Weight::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/weights');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_can_create_weight()
    {
        $weightData = [
            'value_kg' => 75.5,
            'measured_at' => now()->toISOString(),
            'note' => 'Morning weight',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/weights', $weightData);

        $response->assertStatus(201)
                ->assertJson([
                    'value_kg' => '75.50',
                    'note' => 'Morning weight',
                ]);

        $this->assertDatabaseHas('weights', [
            'user_id' => $this->user->id,
            'value_kg' => '75.50',
            'note' => 'Morning weight',
        ]);
    }

    public function test_authenticated_user_can_get_specific_weight()
    {
        $weight = Weight::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/weights/{$weight->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $weight->id,
                    'value_kg' => $weight->value_kg,
                ]);
    }

    public function test_authenticated_user_can_update_weight()
    {
        $weight = Weight::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'value_kg' => 76.0,
            'note' => 'Updated weight',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/weights/{$weight->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'value_kg' => '76.00',
                    'note' => 'Updated weight',
                ]);

        $this->assertDatabaseHas('weights', [
            'id' => $weight->id,
            'value_kg' => '76.00',
            'note' => 'Updated weight',
        ]);
    }

    public function test_authenticated_user_can_delete_weight()
    {
        $weight = Weight::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/weights/{$weight->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('weights', [
            'id' => $weight->id,
        ]);
    }

    public function test_user_cannot_access_other_users_weights()
    {
        $otherUser = User::factory()->create();
        $weight = Weight::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/weights/{$weight->id}");

        $response->assertStatus(404);
    }

    public function test_weight_creation_validation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/weights', [
            'value_kg' => 'invalid',
            'measured_at' => 'invalid-date',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['value_kg', 'measured_at']);
    }

    public function test_unauthenticated_user_cannot_access_weights()
    {
        $response = $this->getJson('/api/v1/weights');

        $response->assertStatus(401);
    }
}
