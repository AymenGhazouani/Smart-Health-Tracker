<?php

namespace Database\Factories;

use App\Models\Psychologist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Psychologist>
 */
class PsychologistFactory extends Factory
{
    protected $model = Psychologist::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialties = [
            'Cognitive Behavioral Therapy',
            'Family Therapy',
            'Child Psychology',
            'Marriage Counseling',
            'Anxiety Treatment',
            'Depression Treatment',
            'Trauma Therapy',
            'Addiction Counseling',
        ];

        return [
            'name' => 'Dr. ' . $this->faker->firstName . ' ' . $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'specialty' => $this->faker->randomElement($specialties),
            'bio' => $this->faker->paragraph(3),
            'availability' => $this->generateAvailability(),
            'hourly_rate' => $this->faker->randomFloat(2, 80, 200),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Generate random availability schedule
     */
    private function generateAvailability(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $availability = [];

        foreach ($days as $day) {
            if ($this->faker->boolean(70)) { // 70% chance of working each day
                $slots = [];
                $numSlots = $this->faker->numberBetween(1, 2);
                
                for ($i = 0; $i < $numSlots; $i++) {
                    $startHour = $this->faker->numberBetween(8, 16);
                    $endHour = $startHour + $this->faker->numberBetween(2, 6);
                    
                    $slots[] = [
                        'start' => sprintf('%02d:00', $startHour),
                        'end' => sprintf('%02d:00', min($endHour, 20)),
                    ];
                }
                
                $availability[$day] = $slots;
            }
        }

        return $availability;
    }

    /**
     * Indicate that the psychologist is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the psychologist is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
