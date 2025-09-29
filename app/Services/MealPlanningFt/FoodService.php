<?php

namespace App\Services\MealPlanningFt;
use App\Models\Food;
class FoodService
{

 public function getAllFood() 
 {
    return Food::all() ; 
 }
 public function getFoodById($id) 
 {
    return Food::findOrFail ($id)  ;    

 }
 public function createFood(array $data) 
  {
    return Food::create($data) ; 
  }
  public function updateFood($id, array $data )
   {
     $food = Food::findOrFail($id) ; 
     $food ->update($data); 
     return $food ; 

   }
   public function deleteFood($id)
    {
        $food = Food::findOrFail($id);
        $food->delete();
    }

}