<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Psychologist;
use App\Models\User;
use App\Models\PsySession;
use App\Models\PsyNote;
use Carbon\Carbon;

class PsychologyVisitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample psychologists
        $psychologists = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@psychology.com',
                'phone' => '+1-555-0101',
                'specialty' => 'Anxiety',
                'bio' => 'Dr. Sarah Johnson specializes in anxiety disorders and cognitive behavioral therapy. With over 10 years of experience, she helps patients develop effective coping strategies.',
                'availability' => [
                    'monday' => [['start' => '09:00', 'end' => '17:00']],
                    'tuesday' => [['start' => '09:00', 'end' => '17:00']],
                    'wednesday' => [['start' => '09:00', 'end' => '17:00']],
                    'thursday' => [['start' => '09:00', 'end' => '17:00']],
                    'friday' => [['start' => '09:00', 'end' => '15:00']],
                ],
                'hourly_rate' => 150.00,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@psychology.com',
                'phone' => '+1-555-0102',
                'specialty' => 'Depression',
                'bio' => 'Dr. Michael Chen is a licensed clinical psychologist specializing in depression and mood disorders. He uses evidence-based treatments to help patients recover.',
                'availability' => [
                    'monday' => [['start' => '10:00', 'end' => '18:00']],
                    'tuesday' => [['start' => '10:00', 'end' => '18:00']],
                    'wednesday' => [['start' => '10:00', 'end' => '18:00']],
                    'thursday' => [['start' => '10:00', 'end' => '18:00']],
                    'saturday' => [['start' => '09:00', 'end' => '13:00']],
                ],
                'hourly_rate' => 140.00,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@psychology.com',
                'phone' => '+1-555-0103',
                'specialty' => 'Trauma',
                'bio' => 'Dr. Emily Rodriguez specializes in trauma therapy and PTSD treatment. She is certified in EMDR and trauma-focused cognitive behavioral therapy.',
                'availability' => [
                    'monday' => [['start' => '08:00', 'end' => '16:00']],
                    'tuesday' => [['start' => '08:00', 'end' => '16:00']],
                    'wednesday' => [['start' => '08:00', 'end' => '16:00']],
                    'friday' => [['start' => '08:00', 'end' => '16:00']],
                ],
                'hourly_rate' => 160.00,
                'is_active' => true,
            ],
        ];

        foreach ($psychologists as $psychologistData) {
            Psychologist::create($psychologistData);
        }

        // Create sample patients (users)
        $patients = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob.wilson@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ],
        ];

        foreach ($patients as $patientData) {
            User::create($patientData);
        }

        // Create sample sessions
        $sessions = [
            [
                'psychologist_id' => 1,
                'patient_id' => 1,
                'start_time' => Carbon::now()->addDays(1)->setTime(10, 0),
                'end_time' => Carbon::now()->addDays(1)->setTime(11, 0),
                'status' => 'booked',
                'notes' => 'Initial consultation for anxiety treatment',
                'session_fee' => 150.00,
            ],
            [
                'psychologist_id' => 1,
                'patient_id' => 2,
                'start_time' => Carbon::now()->addDays(2)->setTime(14, 0),
                'end_time' => Carbon::now()->addDays(2)->setTime(15, 0),
                'status' => 'confirmed',
                'notes' => 'Follow-up session',
                'session_fee' => 150.00,
            ],
            [
                'psychologist_id' => 2,
                'patient_id' => 3,
                'start_time' => Carbon::now()->addDays(3)->setTime(11, 0),
                'end_time' => Carbon::now()->addDays(3)->setTime(12, 0),
                'status' => 'booked',
                'notes' => 'Depression assessment',
                'session_fee' => 140.00,
            ],
            [
                'psychologist_id' => 3,
                'patient_id' => 1,
                'start_time' => Carbon::now()->subDays(1)->setTime(9, 0),
                'end_time' => Carbon::now()->subDays(1)->setTime(10, 0),
                'status' => 'completed',
                'notes' => 'Trauma therapy session completed successfully',
                'session_fee' => 160.00,
            ],
        ];

        foreach ($sessions as $sessionData) {
            PsySession::create($sessionData);
        }

        // Create sample notes for completed session
        $notes = [
            [
                'psy_session_id' => 4, // The completed session
                'psychologist_id' => 3,
                'content' => 'Patient showed significant progress in managing trauma triggers. Discussed grounding techniques and breathing exercises. Patient reported feeling more confident in handling stressful situations.',
                'note_type' => 'session_notes',
                'is_encrypted' => true,
            ],
            [
                'psy_session_id' => 4,
                'psychologist_id' => 3,
                'content' => 'Follow-up plan: Continue with weekly sessions, focus on exposure therapy for specific triggers. Patient to practice mindfulness exercises daily.',
                'note_type' => 'follow_up',
                'is_encrypted' => true,
            ],
        ];

        foreach ($notes as $noteData) {
            PsyNote::create($noteData);
        }

        $this->command->info('Psychology Visits sample data created successfully!');
    }
}

