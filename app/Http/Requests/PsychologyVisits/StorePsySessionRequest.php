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
            'psychologist_id.required' => 'Le psychologue est requis.',
            'psychologist_id.exists' => 'Le psychologue sélectionné n\'existe pas.',
            'patient_id.required' => 'Le patient est requis.',
            'patient_id.exists' => 'Le patient sélectionné n\'existe pas.',
            'start_time.required' => 'L\'heure de début est requise.',
            'start_time.date' => 'Veuillez fournir une heure de début valide.',
            'start_time.after' => 'La séance doit être programmée pour une heure future.',
            'end_time.date' => 'Veuillez fournir une heure de fin valide.',
            'end_time.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'status.in' => 'Statut de séance invalide.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
            'session_fee.numeric' => 'Le tarif de la séance doit être un nombre valide.',
            'session_fee.min' => 'Le tarif de la séance ne peut pas être négatif.',
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

