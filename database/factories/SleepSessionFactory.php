<?php

namespace Database\Factories;

use App\Models\SleepSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SleepSessionFactory extends Factory
{
    protected $model = SleepSession::class;

    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('-45 days', 'now');
        $startTime->setTime($this->faker->numberBetween(21, 23), $this->faker->numberBetween(0, 59));
        
        $duration = $this->faker->numberBetween(360, 540); // 6-9 hours
        $endTime = (clone $startTime)->addMinutes($duration);
        
        return [
            'user_id' => User::factory(),
            'started_at' => $startTime,
            'ended_at' => $endTime,
            'duration_minutes' => $duration,
            'quality' => $this->faker->numberBetween(1, 5),
            'note' => $this->faker->optional(0.3)->sentence(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function goodQuality(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality' => $this->faker->numberBetween(4, 5),
            'duration_minutes' => $this->faker->numberBetween(420, 480),
        ]);
    }
}