<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StorePsySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        return [
            'psychologist_id' => 'required|exists:psychologists,id',
            'patient_id' => 'required|exists:users,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'status' => 'sometimes|in:booked,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
            'session_fee' => 'nullable|numeric|min:0|max:999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'psychologist_id.required' => 'Psychologist is required.',
            'psychologist_id.exists' => 'Selected psychologist does not exist.',
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'start_time.required' => 'Session start time is required.',
            'start_time.date' => 'Please provide a valid start time.',
            'start_time.after' => 'Session must be scheduled for a future time.',
            'end_time.date' => 'Please provide a valid end time.',
            'end_time.after' => 'End time must be after start time.',
            'status.in' => 'Invalid session status.',
            'session_fee.numeric' => 'Session fee must be a valid number.',
            'session_fee.min' => 'Session fee cannot be negative.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Set default end time if not provided (1 hour after start time)
        if ($this->start_time && !$this->end_time) {
            $this->merge([
                'end_time' => Carbon::parse($this->start_time)->addHour()->toDateTimeString()
            ]);
        }
    }
}

