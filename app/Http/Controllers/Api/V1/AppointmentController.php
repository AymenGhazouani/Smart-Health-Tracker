<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AvailabilitySlot;
use App\Notifications\AppointmentBooked;
use App\Notifications\AppointmentCanceled;
use App\Notifications\AppointmentRescheduled;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $appointments = Appointment::with(['user', 'provider.user'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'availability_slot_id' => 'required|exists:availability_slots,id',
            'reason' => 'nullable|string',
        ]);

        $slot = AvailabilitySlot::findOrFail($validated['availability_slot_id']);

        if ($slot->is_booked) {
            return response()->json(['message' => 'This slot is already booked'], 422);
        }

        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'provider_id' => $validated['provider_id'],
            'availability_slot_id' => $validated['availability_slot_id'],
            'scheduled_time' => $slot->start_time,
            'reason' => $validated['reason'] ?? null,
        ]);

        $slot->update(['is_booked' => true]);

        // Notify provider about new appointment
        $appointment->provider->user->notify(new AppointmentBooked($appointment));

        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'availability_slot_id' => 'required|exists:availability_slots,id',
            'reason' => 'nullable|string',
        ]);

        // If the slot has changed
        if ($appointment->availability_slot_id != $validated['availability_slot_id']) {
            // Free up the old slot
            $oldSlot = AvailabilitySlot::find($appointment->availability_slot_id);
            $oldSlot->update(['is_booked' => false]);

            // Book the new slot
            $newSlot = AvailabilitySlot::find($validated['availability_slot_id']);
            if ($newSlot->is_booked) {
                return response()->json(['message' => 'This slot is already booked'], 422);
            }

            $newSlot->update(['is_booked' => true]);
            $validated['scheduled_time'] = $newSlot->start_time;
            $validated['status'] = 'rescheduled';

            // Notify provider about rescheduled appointment
            $appointment->provider->user->notify(new AppointmentRescheduled($appointment));
        }

        $appointment->update($validated);

        return response()->json($appointment);
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'canceled']);

        // Free up the slot
        $slot = AvailabilitySlot::find($appointment->availability_slot_id);
        $slot->update(['is_booked' => false]);

        // Notify provider about canceled appointment
        $appointment->provider->user->notify(new AppointmentCanceled($appointment));

        return response()->json(['message' => 'Appointment canceled successfully']);
    }

    public function summary(Appointment $appointment)
    {
        return response()->json($appointment->visitSummary);
    }
}
