<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePsychologistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        $psychologistId = $this->route('psychologist');
        
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:psychologists,email,' . $psychologistId,
            'phone' => 'sometimes|nullable|string|max:20',
            'specialty' => 'sometimes|string|max:255',
            'bio' => 'sometimes|nullable|string|max:1000',
            'availability' => 'sometimes|nullable|array',
            'availability.*' => 'array',
            'availability.*.*.start' => 'required_with:availability.*.*|string|date_format:H:i',
            'availability.*.*.end' => 'required_with:availability.*.*|string|date_format:H:i|after:availability.*.*.start',
            'hourly_rate' => 'sometimes|nullable|numeric|min:0|max:999999.99',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Psychologist name must be a string.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'specialty.string' => 'Specialty must be a string.',
            'availability.*.*.start.required_with' => 'Start time is required when availability is specified.',
            'availability.*.*.end.required_with' => 'End time is required when availability is specified.',
            'availability.*.*.end.after' => 'End time must be after start time.',
            'hourly_rate.numeric' => 'Hourly rate must be a valid number.',
            'hourly_rate.min' => 'Hourly rate cannot be negative.',
        ];
    }
}

