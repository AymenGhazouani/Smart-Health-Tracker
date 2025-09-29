<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Auth\ApiRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProviderController;
use App\Http\Controllers\Api\V1\AvailabilitySlotController;
use App\Http\Controllers\Api\V1\AppointmentController;
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

// Public API routes
Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
});

Route::prefix('v1')->group(function () {
    Route::get('providers', [ProviderController::class, 'index']);
    Route::get('providers/{provider}', [ProviderController::class, 'show']);

    Route::get('availability-slots', [AvailabilitySlotController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::post('appointments', [AppointmentController::class, 'store']);
        Route::get('appointments/{appointment}', [AppointmentController::class, 'show']);
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update']);
        Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
        Route::get('appointments/{appointment}/summary', [AppointmentController::class, 'summary']);
    });
});
