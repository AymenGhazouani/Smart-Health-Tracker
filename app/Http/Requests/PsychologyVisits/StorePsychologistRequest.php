<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;

class StorePsychologistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:psychologists,email',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'availability' => 'nullable|array',
            'availability.*' => 'array',
            'availability.*.*.start' => 'required_with:availability.*.*|string|date_format:H:i',
            'availability.*.*.end' => 'required_with:availability.*.*|string|date_format:H:i|after:availability.*.*.start',
            'hourly_rate' => 'nullable|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Psychologist name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'specialty.required' => 'Specialty is required.',
            'availability.*.*.start.required_with' => 'Start time is required when availability is specified.',
            'availability.*.*.end.required_with' => 'End time is required when availability is specified.',
            'availability.*.*.end.after' => 'End time must be after start time.',
            'hourly_rate.numeric' => 'Hourly rate must be a valid number.',
            'hourly_rate.min' => 'Hourly rate cannot be negative.',
        ];
    }
}

