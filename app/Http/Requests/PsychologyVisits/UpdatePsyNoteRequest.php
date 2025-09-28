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
            'content' => 'sometimes|string|max:10000',
            'note_type' => 'sometimes|in:session_notes,assessment,follow_up,treatment_plan,progress_notes,other',
            'is_encrypted' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'content.string' => 'Note content must be text.',
            'content.max' => 'Note content cannot exceed 10,000 characters.',
            'note_type.in' => 'Invalid note type.',
        ];
    }
}

