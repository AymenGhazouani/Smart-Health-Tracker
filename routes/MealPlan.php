<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealPlanningFt\Api\V1\FoodController;
use App\Http\Controllers\MealPlanningFt\Api\V1\MealController;
/*
|--------------------------------------------------------------------------
| Meal Planning Routes
|--------------------------------------------------------------------------
|
| Routes for the Meal Planning feature (Foods, Meals, etc.).
| These are prefixed with /api/v1 for versioning.
|
*/
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('foods', FoodController::class);
    Route::apiResource('meals', MealController::class);


});

