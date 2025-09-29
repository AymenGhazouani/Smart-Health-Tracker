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
            'content' => 'required|string|max:10000',
            'note_type' => 'required|in:session_notes,assessment,follow_up,treatment_plan,progress_notes,other',
            'is_encrypted' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Note content is required.',
            'content.string' => 'Note content must be text.',
            'content.max' => 'Note content cannot exceed 10,000 characters.',
            'note_type.required' => 'Note type is required.',
            'note_type.in' => 'Invalid note type.',
            'is_encrypted.boolean' => 'Encryption setting must be true or false.',
        ];
    }
}

