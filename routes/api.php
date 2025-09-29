<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Auth\ApiRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\SpecialtyController;
use App\Http\Controllers\Doctor\DoctorReviewController;
use App\Http\Controllers\Api\V1\WeightController;
use App\Http\Controllers\Api\V1\SleepSessionController;
use App\Http\Controllers\Api\V1\ActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require base_path('routes/MealPlan.php');
require base_path('routes/PsychologyVisits.php');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
// Public API routes
Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    Route::prefix('v1')->group(function () {

        // Doctors CRUD
        Route::apiResource('doctors', DoctorController::class);

        // Specialties CRUD
        Route::apiResource('specialties', SpecialtyController::class);

        // Doctor Reviews
        Route::get('doctors/{doctor}/reviews', [DoctorReviewController::class, 'index']);
        Route::post('doctors/{doctor}/reviews', [DoctorReviewController::class, 'store']);

        // Optional: review CRUD
        Route::get('reviews/{review}', [DoctorReviewController::class, 'show']);
        Route::put('reviews/{review}', [DoctorReviewController::class, 'update']);
        Route::delete('reviews/{review}', [DoctorReviewController::class, 'destroy']);
    });


});

    Route::prefix('v1')->group(function () {
        Route::apiResource('weights', WeightController::class);
        Route::apiResource('sleep-sessions', SleepSessionController::class);
        Route::apiResource('activities', ActivityController::class);
    });
});
