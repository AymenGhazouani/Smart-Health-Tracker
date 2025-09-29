<?php

namespace App\Http\Requests\MealPlanningFt;

use Illuminate\Foundation\Http\FormRequest;

class StoreMealRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Set true if all authenticated users can create/update meals
        return true;
    }

   public function rules():array
{
    return [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'foods' => 'required|array',
        'foods.*.id' => 'required|exists:foods,id',
        'foods.*.quantity' => 'required|integer|min:1'
    ];
}

    public function messages(): array
    {
        return [
            'name.required' => 'Meal name is required.',
            'foods.*.id.exists' => 'Selected food does not exist.',
            'foods.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
