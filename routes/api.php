<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorReviewController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function() {

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