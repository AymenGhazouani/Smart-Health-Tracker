<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Weight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeightTest extends TestCase
{
    use RefreshDatabase;

    public function test_weight_can_be_created()
    {
        $user = User::factory()->create();
        
        $weight = Weight::create([
            'user_id' => $user->id,
            'value_kg' => 75.5,
            'measured_at' => now(),
            'note' => 'Morning weight',
        ]);

        $this->assertDatabaseHas('weights', [
            'user_id' => $user->id,
            'value_kg' => 75.5,
            'note' => 'Morning weight',
        ]);
    }

    public function test_weight_belongs_to_user()
    {
        $user = User::factory()->create();
        $weight = Weight::create([
            'user_id' => $user->id,
            'value_kg' => 75.5,
            'measured_at' => now(),
        ]);

        $this->assertInstanceOf(User::class, $weight->user);
        $this->assertEquals($user->id, $weight->user->id);
    }

    public function test_weight_value_kg_is_cast_to_decimal()
    {
        $user = User::factory()->create();
        $weight = Weight::create([
            'user_id' => $user->id,
            'value_kg' => '75.50',
            'measured_at' => now(),
        ]);

        $this->assertIsNumeric($weight->value_kg);
        $this->assertEquals('75.50', $weight->value_kg);
    }

    public function test_weight_measured_at_is_cast_to_datetime()
    {
        $user = User::factory()->create();
        $weight = Weight::create([
            'user_id' => $user->id,
            'value_kg' => 75.5,
            'measured_at' => now(),
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $weight->measured_at);
    }

    public function test_weight_can_be_created_without_note()
    {
        $user = User::factory()->create();
        
        $weight = Weight::create([
            'user_id' => $user->id,
            'value_kg' => 75.5,
            'measured_at' => now(),
        ]);

        $this->assertDatabaseHas('weights', [
            'user_id' => $user->id,
            'value_kg' => 75.5,
            'note' => null,
        ]);
    }
}
