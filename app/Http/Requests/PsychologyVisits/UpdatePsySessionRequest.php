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
            'psychologist_id.exists' => 'Le psychologue sélectionné n\'existe pas.',
            'patient_id.exists' => 'Le patient sélectionné n\'existe pas.',
            'start_time.date' => 'Veuillez fournir une heure de début valide.',
            'end_time.date' => 'Veuillez fournir une heure de fin valide.',
            'end_time.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'status.in' => 'Statut de séance invalide.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
            'session_fee.numeric' => 'Le tarif de la séance doit être un nombre valide.',
            'session_fee.min' => 'Le tarif de la séance ne peut pas être négatif.',
        ];
    }
}

