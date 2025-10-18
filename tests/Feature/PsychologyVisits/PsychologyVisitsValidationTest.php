<?php

namespace Tests\Feature\PsychologyVisits;

use Tests\TestCase;
use App\Models\User;
use App\Models\Psychologist;
use App\Models\PsySession;
use App\Models\PsyNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class PsychologyVisitsValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_psychologist_validation_rules()
    {
        // Test valid data
        $validData = [
            'name' => 'Dr. John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 123-4567',
            'specialty' => 'Cognitive Behavioral Therapy',
            'bio' => 'Experienced psychologist with 10 years of practice.',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ];

        $validator = Validator::make($validData, Psychologist::validationRules());
        $this->assertTrue($validator->passes());

        // Test invalid data
        $invalidData = [
            'name' => 'A', // Too short
            'email' => 'invalid-email', // Invalid email format
            'phone' => 'abc123', // Invalid phone format
            'specialty' => '', // Empty specialty
            'hourly_rate' => -50, // Negative rate
        ];

        $validator = Validator::make($invalidData, Psychologist::validationRules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
        $this->assertArrayHasKey('specialty', $validator->errors()->toArray());
        $this->assertArrayHasKey('hourly_rate', $validator->errors()->toArray());
    }

    public function test_psy_session_validation_rules()
    {
        $user = User::factory()->create();
        $psychologist = Psychologist::factory()->create();

        // Test valid data
        $validData = [
            'psychologist_id' => $psychologist->id,
            'patient_id' => $user->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'status' => 'booked',
            'notes' => 'Initial consultation',
            'session_fee' => 150.00,
        ];

        $validator = Validator::make($validData, PsySession::validationRules());
        $this->assertTrue($validator->passes());

        // Test invalid data
        $invalidData = [
            'psychologist_id' => 999, // Non-existent psychologist
            'patient_id' => 999, // Non-existent patient
            'start_time' => now()->subDay(), // Past date
            'end_time' => now()->subDay(), // End before start
            'status' => 'invalid_status', // Invalid status
            'session_fee' => -100, // Negative fee
        ];

        $validator = Validator::make($invalidData, PsySession::validationRules());
        $this->assertFalse($validator->passes());
    }

    public function test_psy_note_validation_rules()
    {
        $user = User::factory()->create();
        $psychologist = Psychologist::factory()->create();
        $session = PsySession::factory()->create([
            'psychologist_id' => $psychologist->id,
            'patient_id' => $user->id,
        ]);

        // Test valid data
        $validData = [
            'psy_session_id' => $session->id,
            'psychologist_id' => $psychologist->id,
            'content' => 'Patient showed improvement in anxiety levels.',
            'note_type' => 'session_notes',
            'is_encrypted' => true,
        ];

        $validator = Validator::make($validData, PsyNote::validationRules());
        $this->assertTrue($validator->passes());

        // Test invalid data
        $invalidData = [
            'psy_session_id' => 999, // Non-existent session
            'psychologist_id' => 999, // Non-existent psychologist
            'content' => '', // Empty content
            'note_type' => 'invalid_type', // Invalid note type
        ];

        $validator = Validator::make($invalidData, PsyNote::validationRules());
        $this->assertFalse($validator->passes());
    }

    public function test_availability_validation()
    {
        // Test valid availability format
        $validAvailability = [
            'monday' => [
                ['start' => '09:00', 'end' => '17:00']
            ],
            'tuesday' => [
                ['start' => '09:00', 'end' => '12:00'],
                ['start' => '14:00', 'end' => '17:00']
            ],
        ];

        $validData = [
            'name' => 'Dr. Jane Doe',
            'email' => 'jane.doe@example.com',
            'specialty' => 'Family Therapy',
            'availability' => $validAvailability,
        ];

        $validator = Validator::make($validData, Psychologist::validationRules());
        $this->assertTrue($validator->passes());

        // Test invalid availability format
        $invalidAvailability = [
            'monday' => [
                ['start' => '17:00', 'end' => '09:00'] // End before start
            ],
        ];

        $invalidData = [
            'name' => 'Dr. Jane Doe',
            'email' => 'jane.doe@example.com',
            'specialty' => 'Family Therapy',
            'availability' => $invalidAvailability,
        ];

        $validator = Validator::make($invalidData, Psychologist::validationRules());
        $this->assertFalse($validator->passes());
    }
}
