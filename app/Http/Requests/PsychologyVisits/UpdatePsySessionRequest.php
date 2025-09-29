<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdatePsySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        return [
            'psychologist_id' => 'sometimes|exists:psychologists,id',
            'patient_id' => 'sometimes|exists:users,id',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'status' => 'sometimes|in:booked,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'sometimes|nullable|string|max:1000',
            'session_fee' => 'sometimes|nullable|numeric|min:0|max:999999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'psychologist_id.exists' => 'Selected psychologist does not exist.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'start_time.date' => 'Please provide a valid start time.',
            'end_time.date' => 'Please provide a valid end time.',
            'end_time.after' => 'End time must be after start time.',
            'status.in' => 'Invalid session status.',
            'session_fee.numeric' => 'Session fee must be a valid number.',
            'session_fee.min' => 'Session fee cannot be negative.',
        ];
    }
}

