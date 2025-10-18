<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePsyNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        return [
            'psy_session_id' => 'sometimes|exists:psy_sessions,id',
            'psychologist_id' => 'sometimes|exists:psychologists,id',
            'content' => 'sometimes|string|max:10000|min:1',
            'note_type' => 'sometimes|in:session_notes,assessment,follow_up,treatment_plan,progress_notes,other',
            'is_encrypted' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'psy_session_id.exists' => 'La séance sélectionnée n\'existe pas.',
            'psychologist_id.exists' => 'Le psychologue sélectionné n\'existe pas.',
            'content.string' => 'Le contenu de la note doit être du texte.',
            'content.min' => 'Le contenu de la note doit contenir au moins 1 caractère.',
            'content.max' => 'Le contenu de la note ne peut pas dépasser 10 000 caractères.',
            'note_type.in' => 'Type de note invalide.',
            'is_encrypted.boolean' => 'Le paramètre de chiffrement doit être vrai ou faux.',
        ];
    }
}

