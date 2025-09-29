<?php

namespace App\Services\MealPlanningFt ;
use App\Models\Meal ;

class MealService 
{
public function getAllMeals()
    {
        return Meal::with('foods')->get();
    }

    public function getMealById($id)
    {
        return Meal::with('foods')->findOrFail($id);
    }

    public function createMeal(array $data)
    {
        $meal = Meal::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (!empty($data['foods'])) {
            $syncData = [];
            foreach ($data['foods'] as $food) {
                $syncData[$food['id']] = ['quantity' => $food['quantity'] ?? 1];
            }
            $meal->foods()->sync($syncData);
        }

        return $meal->load('foods');
    }

    public function updateMeal($id, array $data)
    {
        $meal = Meal::findOrFail($id);
        $meal->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (isset($data['foods'])) {
            $syncData = [];
            foreach ($data['foods'] as $food) {
                $syncData[$food['id']] = ['quantity' => $food['quantity'] ?? 1];
            }
            $meal->foods()->sync($syncData);
        }

        return $meal->load('foods');
    }

    public function deleteMeal($id)
    {
        $meal = Meal::findOrFail($id);
        $meal->foods()->detach(); // optional, cascade handles it
        $meal->delete();
        return true;
    }
}