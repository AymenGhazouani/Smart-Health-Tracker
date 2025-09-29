<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AvailabilitySlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VisitSummaryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to appropriate dashboard based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Protected routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Provider routes
    Route::resource('providers', ProviderController::class);

    // Availability Slots routes
    Route::resource('availability-slots', AvailabilitySlotController::class);

    // Appointment routes
    Route::get('appointments/get-available-slots', [AppointmentController::class, 'getAvailableSlots'])
        ->name('appointments.get-available-slots');
    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
    Route::resource('appointments', AppointmentController::class);

    // Visit Summary routes
    Route::get('appointments/{appointment}/visit-summary/create', [VisitSummaryController::class, 'create'])
        ->name('visit-summaries.create');
    Route::resource('visit-summaries', VisitSummaryController::class)
        ->except(['index', 'create', 'destroy']);
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);

    // Admin specific provider management
    Route::resource('providers', ProviderController::class);
    Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('appointments.index');
});

require __DIR__.'/auth.php';
