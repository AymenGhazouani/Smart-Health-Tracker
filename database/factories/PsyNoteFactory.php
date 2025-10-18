<?php

namespace Database\Factories;

use App\Models\PsyNote;
use App\Models\PsySession;
use App\Models\Psychologist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PsyNote>
 */
class PsyNoteFactory extends Factory
{
    protected $model = PsyNote::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $noteTypes = [
            'session_notes',
            'assessment',
            'follow_up',
            'treatment_plan',
            'progress_notes',
            'other',
        ];

        return [
            'psy_session_id' => PsySession::factory(),
            'psychologist_id' => Psychologist::factory(),
            'content' => $this->faker->paragraphs(3, true),
            'note_type' => $this->faker->randomElement($noteTypes),
            'is_encrypted' => $this->faker->boolean(80), // 80% chance of being encrypted
        ];
    }

    /**
     * Indicate that the note is encrypted.
     */
    public function encrypted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_encrypted' => true,
        ]);
    }

    /**
     * Indicate that the note is not encrypted.
     */
    public function unencrypted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_encrypted' => false,
        ]);
    }

    /**
     * Indicate that the note is a session note.
     */
    public function sessionNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'note_type' => 'session_notes',
        ]);
    }

    /**
     * Indicate that the note is an assessment.
     */
    public function assessment(): static
    {
        return $this->state(fn (array $attributes) => [
            'note_type' => 'assessment',
        ]);
    }

    /**
     * Indicate that the note is a treatment plan.
     */
    public function treatmentPlan(): static
    {
        return $this->state(fn (array $attributes) => [
            'note_type' => 'treatment_plan',
        ]);
    }
}
