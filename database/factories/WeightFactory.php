<?php

namespace Database\Factories;

use App\Models\Weight;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class WeightFactory extends Factory
{
    protected $model = Weight::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'value_kg' => $this->faker->randomFloat(2, 50, 120),
            'measured_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
            'note' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function recentDays(int $days = 30): static
    {
        return $this->state(fn (array $attributes) => [
            'measured_at' => $this->faker->dateTimeBetween("-{$days} days", 'now'),
        ]);
    }
}