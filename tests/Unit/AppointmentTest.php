<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Provider;
use App\Models\AvailabilitySlot;
use App\Models\Appointment;
use App\Models\VisitSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_can_be_created()
    {
        $user = User::factory()->create();
        $provider = Provider::create([
            'user_id' => $user->id,
            'specialty' => 'Cardiology',
            'bio' => 'Heart specialist',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ]);

        $availabilitySlot = AvailabilitySlot::create([
            'provider_id' => $provider->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'is_available' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'scheduled_time' => now()->addDay(),
            'status' => 'scheduled',
            'meeting_link' => 'https://meet.example.com/123',
            'reason' => 'Regular checkup',
        ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'status' => 'scheduled',
            'meeting_link' => 'https://meet.example.com/123',
            'reason' => 'Regular checkup',
        ]);
    }

    public function test_appointment_belongs_to_user()
    {
        $user = User::factory()->create();
        $provider = Provider::create([
            'user_id' => $user->id,
            'specialty' => 'Cardiology',
            'bio' => 'Heart specialist',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ]);

        $availabilitySlot = AvailabilitySlot::create([
            'provider_id' => $provider->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'is_available' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'scheduled_time' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(User::class, $appointment->user);
        $this->assertEquals($user->id, $appointment->user->id);
    }

    public function test_appointment_belongs_to_provider()
    {
        $user = User::factory()->create();
        $provider = Provider::create([
            'user_id' => $user->id,
            'specialty' => 'Cardiology',
            'bio' => 'Heart specialist',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ]);

        $availabilitySlot = AvailabilitySlot::create([
            'provider_id' => $provider->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'is_available' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'scheduled_time' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(Provider::class, $appointment->provider);
        $this->assertEquals($provider->id, $appointment->provider->id);
    }

    public function test_appointment_belongs_to_availability_slot()
    {
        $user = User::factory()->create();
        $provider = Provider::create([
            'user_id' => $user->id,
            'specialty' => 'Cardiology',
            'bio' => 'Heart specialist',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ]);

        $availabilitySlot = AvailabilitySlot::create([
            'provider_id' => $provider->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'is_available' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'scheduled_time' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(AvailabilitySlot::class, $appointment->availabilitySlot);
        $this->assertEquals($availabilitySlot->id, $appointment->availabilitySlot->id);
    }

    public function test_appointment_has_one_visit_summary()
    {
        $user = User::factory()->create();
        $provider = Provider::create([
            'user_id' => $user->id,
            'specialty' => 'Cardiology',
            'bio' => 'Heart specialist',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ]);

        $availabilitySlot = AvailabilitySlot::create([
            'provider_id' => $provider->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'is_available' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'scheduled_time' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $visitSummary = VisitSummary::create([
            'appointment_id' => $appointment->id,
            'diagnosis' => 'Healthy',
            'treatment_plan' => 'Continue current lifestyle',
            'notes' => 'Patient is in good health',
        ]);

        $this->assertInstanceOf(VisitSummary::class, $appointment->visitSummary);
        $this->assertEquals($visitSummary->id, $appointment->visitSummary->id);
    }

    public function test_appointment_scheduled_time_is_cast_to_datetime()
    {
        $user = User::factory()->create();
        $provider = Provider::create([
            'user_id' => $user->id,
            'specialty' => 'Cardiology',
            'bio' => 'Heart specialist',
            'hourly_rate' => 150.00,
            'is_active' => true,
        ]);

        $availabilitySlot = AvailabilitySlot::create([
            'provider_id' => $provider->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'is_available' => true,
        ]);

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'availability_slot_id' => $availabilitySlot->id,
            'scheduled_time' => now()->addDay(),
            'status' => 'scheduled',
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $appointment->scheduled_time);
    }
}
