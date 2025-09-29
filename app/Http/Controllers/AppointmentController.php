<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Provider;
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

        if ($user->role === 'admin') {
            $appointments = Appointment::with(['user', 'provider.user'])->latest()->get();
        } else if ($user->provider) {
            $appointments = Appointment::with(['user', 'provider.user'])
                ->where('provider_id', $user->provider->id)
                ->latest()->get();
        } else {
            $appointments = Appointment::with(['user', 'provider.user'])
                ->where('user_id', $user->id)
                ->latest()->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $providers = Provider::with('user')->where('is_active', true)->get();
        return view('appointments.create', compact('providers'));
    }

    public function getAvailableSlots(Request $request)
    {
        $provider_id = $request->provider_id;
        $slots = AvailabilitySlot::where('provider_id', $provider_id)
            ->where('is_booked', false)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();

        return response()->json($slots);
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
            return redirect()->back()->with('error', 'This slot is already booked.');
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
        $provider = Provider::with('user')->find($validated['provider_id']);
        $provider->user->notify(new AppointmentBooked($appointment));

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $providers = Provider::with('user')->where('is_active', true)->get();
        $slots = AvailabilitySlot::where('provider_id', $appointment->provider_id)
            ->where('is_booked', false)
            ->orWhere('id', $appointment->availability_slot_id)
            ->where('start_time', '>', now())
            ->orderBy('start_time')
            ->get();

        return view('appointments.edit', compact('appointment', 'providers', 'slots'));
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
                return redirect()->back()->with('error', 'This slot is already booked.');
            }

            $newSlot->update(['is_booked' => true]);
            $validated['scheduled_time'] = $newSlot->start_time;
            $validated['status'] = 'rescheduled';

            // Notify provider about rescheduled appointment
            $appointment->provider->user->notify(new AppointmentRescheduled($appointment));
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'canceled']);

        // Free up the slot
        $slot = AvailabilitySlot::find($appointment->availability_slot_id);
        $slot->update(['is_booked' => false]);

        // Notify provider about canceled appointment
        $appointment->provider->user->notify(new AppointmentCanceled($appointment));

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment canceled successfully.');
    }
}
