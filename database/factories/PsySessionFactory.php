<?php

namespace Database\Factories;

use App\Models\PsySession;
use App\Models\Psychologist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PsySession>
 */
class PsySessionFactory extends Factory
{
    protected $model = PsySession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 month');
        $endTime = clone $startTime;
        $endTime->modify('+1 hour');

        $statuses = ['booked', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'];

        return [
            'psychologist_id' => Psychologist::factory(),
            'patient_id' => User::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional(0.7)->paragraph(),
            'session_fee' => $this->faker->randomFloat(2, 80, 200),
        ];
    }

    /**
     * Indicate that the session is booked.
     */
    public function booked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'booked',
        ]);
    }

    /**
     * Indicate that the session is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the session is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the session is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the session is in the past.
     */
    public function past(): static
    {
        $startTime = $this->faker->dateTimeBetween('-1 month', '-1 day');
        $endTime = clone $startTime;
        $endTime->modify('+1 hour');

        return $this->state(fn (array $attributes) => [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }

    /**
     * Indicate that the session is in the future.
     */
    public function future(): static
    {
        $startTime = $this->faker->dateTimeBetween('+1 day', '+1 month');
        $endTime = clone $startTime;
        $endTime->modify('+1 hour');

        return $this->state(fn (array $attributes) => [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }
}
