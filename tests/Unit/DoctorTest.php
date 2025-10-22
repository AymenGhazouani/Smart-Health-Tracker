<?php

namespace Tests\Unit;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\DoctorReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_be_created()
    {
        $specialty = Specialty::create([
            'name' => 'Cardiology',
            'description' => 'Heart specialist',
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. John Smith',
            'email' => 'john.smith@example.com',
            'specialty_id' => $specialty->id,
            'phone' => '+1234567890',
            'description' => 'Experienced cardiologist',
        ]);

        $this->assertDatabaseHas('doctors', [
            'name' => 'Dr. John Smith',
            'email' => 'john.smith@example.com',
            'specialty_id' => $specialty->id,
            'phone' => '+1234567890',
            'description' => 'Experienced cardiologist',
        ]);
    }

    public function test_doctor_belongs_to_specialty()
    {
        $specialty = Specialty::create([
            'name' => 'Cardiology',
            'description' => 'Heart specialist',
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. John Smith',
            'email' => 'john.smith@example.com',
            'specialty_id' => $specialty->id,
            'phone' => '+1234567890',
        ]);

        $this->assertInstanceOf(Specialty::class, $doctor->specialty);
        $this->assertEquals($specialty->id, $doctor->specialty->id);
    }

    public function test_doctor_has_many_reviews()
    {
        $specialty = Specialty::create([
            'name' => 'Cardiology',
            'description' => 'Heart specialist',
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. John Smith',
            'email' => 'john.smith@example.com',
            'specialty_id' => $specialty->id,
            'phone' => '+1234567890',
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $review1 = DoctorReview::create([
            'doctor_id' => $doctor->id,
            'user_id' => $user1->id,
            'rating' => 5,
            'comment' => 'Great doctor!',
        ]);

        $review2 = DoctorReview::create([
            'doctor_id' => $doctor->id,
            'user_id' => $user2->id,
            'rating' => 4,
            'comment' => 'Very good service.',
        ]);

        $this->assertCount(2, $doctor->reviews);
        $this->assertTrue($doctor->reviews->contains($review1));
        $this->assertTrue($doctor->reviews->contains($review2));
    }

    public function test_doctor_can_be_created_with_minimal_data()
    {
        $specialty = Specialty::create([
            'name' => 'Cardiology',
            'description' => 'Heart specialist',
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. Jane Doe',
            'email' => 'jane.doe@example.com',
            'specialty_id' => $specialty->id,
        ]);

        $this->assertDatabaseHas('doctors', [
            'name' => 'Dr. Jane Doe',
            'email' => 'jane.doe@example.com',
            'specialty_id' => $specialty->id,
            'phone' => null,
            'description' => null,
        ]);
    }
}
