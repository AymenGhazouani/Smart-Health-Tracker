<?php

namespace App\Http\Requests\PsychologyVisits;

use Illuminate\Foundation\Http\FormRequest;

class StorePsyNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by middleware
    }

    public function rules(): array
    {
        return [
            'psy_session_id' => 'required|exists:psy_sessions,id',
            'psychologist_id' => 'required|exists:psychologists,id',
            'content' => 'required|string|max:10000',
            'note_type' => 'sometimes|in:session_notes,assessment,follow_up,treatment_plan,progress_notes,other',
            'is_encrypted' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'psy_session_id.required' => 'Session is required.',
            'psy_session_id.exists' => 'Selected session does not exist.',
            'psychologist_id.required' => 'Psychologist is required.',
            'psychologist_id.exists' => 'Selected psychologist does not exist.',
            'content.required' => 'Note content is required.',
            'content.string' => 'Note content must be text.',
            'content.max' => 'Note content cannot exceed 10,000 characters.',
            'note_type.in' => 'Invalid note type.',
        ];
    }
}

