<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
    
    // Client meal planning routes
    Route::prefix('meals')->name('client.meals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\MealPlanController::class, 'index'])->name('index');
        Route::get('/foods', [\App\Http\Controllers\Client\MealPlanController::class, 'foods'])->name('foods');
        Route::get('/create', [\App\Http\Controllers\Client\MealPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Client\MealPlanController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Client\MealPlanController::class, 'show'])->name('show');
    });
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);
    Route::resource('meals', \App\Http\Controllers\MealPlanningFt\Api\V1\MealController::class);
});

require __DIR__.'/auth.php';
