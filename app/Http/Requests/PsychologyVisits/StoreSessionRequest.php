<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        return [
            'psychologist_id' => 'required|exists:psychologists,id',
            'session_date' => 'required|date|after_or_equal:today',
            'session_time' => 'required|string|date_format:H:i',
            'duration' => 'nullable|integer|min:30|max:180',
            'session_type' => 'nullable|string|in:individual,couples,family,group',
            'reason' => 'nullable|string|max:1000',
            'special_requests' => 'nullable|string|max:500',
            'terms_accepted' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'psychologist_id.required' => 'Le psychologue est requis.',
            'psychologist_id.exists' => 'Le psychologue sélectionné n\'existe pas.',
            'session_date.required' => 'La date de la séance est requise.',
            'session_date.date' => 'Veuillez fournir une date valide.',
            'session_date.after_or_equal' => 'La séance doit être programmée pour aujourd\'hui ou plus tard.',
            'session_time.required' => 'L\'heure de la séance est requise.',
            'session_time.date_format' => 'L\'heure doit être au format HH:MM (ex: 14:30).',
            'duration.integer' => 'La durée doit être un nombre entier.',
            'duration.min' => 'La durée minimale est de 30 minutes.',
            'duration.max' => 'La durée maximale est de 180 minutes.',
            'session_type.in' => 'Type de séance invalide. Valeurs autorisées: individual, couples, family, group.',
            'reason.max' => 'La raison ne peut pas dépasser 1000 caractères.',
            'special_requests.max' => 'Les demandes spéciales ne peuvent pas dépasser 500 caractères.',
            'terms_accepted.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'terms_accepted.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Validate that the session time is within psychologist availability
        if ($this->session_date && $this->session_time && $this->psychologist_id) {
            $this->validatePsychologistAvailability();
        }
    }

    private function validatePsychologistAvailability(): void
    {
        try {
            $psychologist = \App\Models\Psychologist::find($this->psychologist_id);
            
            if (!$psychologist) {
                return;
            }

            $sessionDateTime = Carbon::parse($this->session_date . ' ' . $this->session_time);
            $dayOfWeek = strtolower($sessionDateTime->format('l'));

            // Check if psychologist is available on this day
            if (!$psychologist->availability || !isset($psychologist->availability[$dayOfWeek])) {
                $this->merge(['availability_error' => 'Le psychologue n\'est pas disponible ce jour-là.']);
                return;
            }

            // Check if the time falls within available slots
            $sessionTime = $sessionDateTime->format('H:i');
            $isAvailable = false;

            foreach ($psychologist->availability[$dayOfWeek] as $slot) {
                if ($sessionTime >= $slot['start'] && $sessionTime <= $slot['end']) {
                    $isAvailable = true;
                    break;
                }
            }

            if (!$isAvailable) {
                $this->merge(['availability_error' => 'Le psychologue n\'est pas disponible à cette heure.']);
            }

        } catch (\Exception $e) {
            $this->merge(['availability_error' => 'Erreur lors de la vérification de la disponibilité.']);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('availability_error')) {
                $validator->errors()->add('session_time', $this->availability_error);
            }
        });
    }
}
