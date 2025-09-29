<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\VisitSummary;
use Illuminate\Http\Request;

class VisitSummaryController extends Controller
{
    public function create(Appointment $appointment)
    {
        if ($appointment->status !== 'completed') {
            $appointment->update(['status' => 'completed']);
        }

        $visitSummary = $appointment->visitSummary;

        if (!$visitSummary) {
            return view('visit-summaries.create', compact('appointment'));
        }

        return redirect()->route('visit-summaries.edit', $visitSummary);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'prescriptions' => 'nullable|string',
            'follow_up_required' => 'boolean',
        ]);

        $visitSummary = VisitSummary::create($validated);

        return redirect()->route('visit-summaries.show', $visitSummary)
            ->with('success', 'Visit summary created successfully.');
    }

    public function show(VisitSummary $visitSummary)
    {
        return view('visit-summaries.show', compact('visitSummary'));
    }

    public function edit(VisitSummary $visitSummary)
    {
        return view('visit-summaries.edit', compact('visitSummary'));
    }

    public function update(Request $request, VisitSummary $visitSummary)
    {
        $validated = $request->validate([
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'prescriptions' => 'nullable|string',
            'follow_up_required' => 'boolean',
        ]);

        $visitSummary->update($validated);

        return redirect()->route('visit-summaries.show', $visitSummary)
            ->with('success', 'Visit summary updated successfully.');
    }
}
