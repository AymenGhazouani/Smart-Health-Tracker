<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        $types = ['running', 'cycling', 'swimming', 'walking', 'gym', 'yoga', 'dancing', 'hiking', 'sports'];
        $type = $this->faker->randomElement($types);
        
        $duration = $this->faker->numberBetween(15, 120);
        $calories = $duration * $this->faker->numberBetween(5, 12);
        
        return [
            'user_id' => User::factory(),
            'type' => $type,
            'duration_minutes' => $duration,
            'calories' => $calories,
            'distance_km_times100' => $this->faker->optional(0.6)->numberBetween(100, 2000),
            'performed_at' => $this->faker->dateTimeBetween('-60 days', 'now'),
            'note' => $this->faker->optional(0.4)->sentence(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function running(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'running',
            'duration_minutes' => $this->faker->numberBetween(20, 90),
            'distance_km_times100' => $this->faker->numberBetween(300, 1500),
        ]);
    }
}