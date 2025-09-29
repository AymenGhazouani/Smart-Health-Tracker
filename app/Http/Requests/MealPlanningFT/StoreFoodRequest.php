<?php
namespace App\Http\Requests\MealPlanningFt ;
use Illuminate\Foundation\Http\FormRequest ;

class StoreFoodRequest extends FormRequest 
{ 
public function authorize():bool 
{
return true ; 
}




public function rules(): array
    {
        return [
            'name'     => 'required|string|min:0',
            'calories' => 'required|integer|min:0',
            'protein'  => 'nullable|numeric|min:0',
            'carbs'    => 'nullable|numeric|min:0',
            'fat'      => 'nullable|numeric|min:0',
        ];
    }
}