<?php

namespace App\Http\Controllers;

use App\Models\AvailabilitySlot;
use App\Models\Provider;
use Illuminate\Http\Request;

class AvailabilitySlotController extends Controller
{
    public function index(Request $request)
    {
        $provider_id = $request->query('provider_id');
        $slots = AvailabilitySlot::when($provider_id, function($query) use ($provider_id) {
            return $query->where('provider_id', $provider_id);
        })->orderBy('start_time')->get();

        $providers = Provider::all();
        return view('availability.index', compact('slots', 'providers', 'provider_id'));
    }

    public function create()
    {
        $providers = Provider::where('is_active', true)->get();
        return view('availability.create', compact('providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        AvailabilitySlot::create($validated);

        return redirect()->route('availability-slots.index')
            ->with('success', 'Availability slot created successfully.');
    }

    public function edit(AvailabilitySlot $availabilitySlot)
    {
        $providers = Provider::where('is_active', true)->get();
        return view('availability.edit', compact('availabilitySlot', 'providers'));
    }

    public function update(Request $request, AvailabilitySlot $availabilitySlot)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_booked' => 'boolean',
        ]);

        $availabilitySlot->update($validated);

        return redirect()->route('availability-slots.index')
            ->with('success', 'Availability slot updated successfully.');
    }

    public function destroy(AvailabilitySlot $availabilitySlot)
    {
        $availabilitySlot->delete();

        return redirect()->route('availability-slots.index')
            ->with('success', 'Availability slot deleted successfully.');
    }
}
