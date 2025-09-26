<?php

namespace App\Http\Requests\MealPlanningFt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'sometimes|string|min:1',
            'calories' => 'sometimes|integer|min:0',
            'protein'  => 'sometimes|numeric|min:0',
            'carbs'    => 'sometimes|numeric|min:0',
            'fat'      => 'sometimes|numeric|min:0',
        ];
    }
}
