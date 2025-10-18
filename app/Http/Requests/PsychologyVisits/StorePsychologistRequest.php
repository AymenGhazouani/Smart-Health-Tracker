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
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|unique:psychologists,email|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'specialty' => 'required|string|max:255|min:2',
            'bio' => 'nullable|string|max:1000',
            'availability' => 'nullable|array',
            'availability.*' => 'array',
            'availability.*.*.start' => 'required_with:availability.*.*|string|date_format:H:i|before:availability.*.*.end',
            'availability.*.*.end' => 'required_with:availability.*.*|string|date_format:H:i|after:availability.*.*.start',
            'hourly_rate' => 'nullable|numeric|min:0|max:999999.99|decimal:0,2',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du psychologue est requis.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà enregistrée.',
            'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
            'phone.regex' => 'Le numéro de téléphone doit contenir uniquement des chiffres, espaces, tirets et parenthèses.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'specialty.required' => 'La spécialité est requise.',
            'specialty.min' => 'La spécialité doit contenir au moins 2 caractères.',
            'specialty.max' => 'La spécialité ne peut pas dépasser 255 caractères.',
            'bio.max' => 'La biographie ne peut pas dépasser 1000 caractères.',
            'availability.*.*.start.required_with' => 'L\'heure de début est requise quand la disponibilité est spécifiée.',
            'availability.*.*.start.date_format' => 'L\'heure de début doit être au format HH:MM.',
            'availability.*.*.start.before' => 'L\'heure de début doit être avant l\'heure de fin.',
            'availability.*.*.end.required_with' => 'L\'heure de fin est requise quand la disponibilité est spécifiée.',
            'availability.*.*.end.date_format' => 'L\'heure de fin doit être au format HH:MM.',
            'availability.*.*.end.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'hourly_rate.numeric' => 'Le tarif horaire doit être un nombre valide.',
            'hourly_rate.min' => 'Le tarif horaire ne peut pas être négatif.',
            'hourly_rate.max' => 'Le tarif horaire ne peut pas dépasser 999999.99.',
            'hourly_rate.decimal' => 'Le tarif horaire doit avoir au maximum 2 décimales.',
            'is_active.boolean' => 'Le statut actif doit être vrai ou faux.',
        ];
    }
}

