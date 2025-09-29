<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Metrics\MetricsDashboardController;
use App\Http\Controllers\Metrics\WeightsDetailController;
use App\Http\Controllers\Metrics\SleepDetailController;
use App\Http\Controllers\Metrics\ActivitiesDetailController;
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
    Route::get('/metrics', [MetricsDashboardController::class, 'index'])->name('metrics.dashboard');
    
    // User metrics routes
    Route::get('/metrics/weights', [WeightsDetailController::class, 'index'])->name('metrics.weights');
    Route::get('/metrics/weights/create', [WeightsDetailController::class, 'create'])->name('metrics.weights.create');
    Route::post('/metrics/weights', [WeightsDetailController::class, 'store'])->name('metrics.weights.store');
    Route::get('/metrics/weights/{weight}/edit', [WeightsDetailController::class, 'edit'])->name('metrics.weights.edit');
    Route::put('/metrics/weights/{weight}', [WeightsDetailController::class, 'update'])->name('metrics.weights.update');
    Route::delete('/metrics/weights/{weight}', [WeightsDetailController::class, 'destroy'])->name('metrics.weights.destroy');
    
    Route::get('/metrics/sleep', [SleepDetailController::class, 'index'])->name('metrics.sleep');
    Route::get('/metrics/sleep/create', [SleepDetailController::class, 'create'])->name('metrics.sleep.create');
    Route::post('/metrics/sleep', [SleepDetailController::class, 'store'])->name('metrics.sleep.store');
    Route::get('/metrics/sleep/{sleepSession}/edit', [SleepDetailController::class, 'edit'])->name('metrics.sleep.edit');
    Route::put('/metrics/sleep/{sleepSession}', [SleepDetailController::class, 'update'])->name('metrics.sleep.update');
    Route::delete('/metrics/sleep/{sleepSession}', [SleepDetailController::class, 'destroy'])->name('metrics.sleep.destroy');
    
    Route::get('/metrics/activities', [ActivitiesDetailController::class, 'index'])->name('metrics.activities');
    Route::get('/metrics/activities/create', [ActivitiesDetailController::class, 'create'])->name('metrics.activities.create');
    Route::post('/metrics/activities', [ActivitiesDetailController::class, 'store'])->name('metrics.activities.store');
    Route::get('/metrics/activities/{activity}/edit', [ActivitiesDetailController::class, 'edit'])->name('metrics.activities.edit');
    Route::put('/metrics/activities/{activity}', [ActivitiesDetailController::class, 'update'])->name('metrics.activities.update');
    Route::delete('/metrics/activities/{activity}', [ActivitiesDetailController::class, 'destroy'])->name('metrics.activities.destroy');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);

    // Admin metrics dashboard
    Route::get('/metrics', [\App\Http\Controllers\Admin\Metrics\AdminMetricsController::class, 'index'])->name('metrics.dashboard');

    // Admin metrics view-only + notifications
    Route::get('/metrics/{user}/weights', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'index'])->name('metrics.weights.index');
    Route::post('/metrics/{user}/weights/notify', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'notifyUser'])->name('metrics.weights.notify');

    Route::get('/metrics/{user}/sleep', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'index'])->name('metrics.sleep.index');
    Route::post('/metrics/{user}/sleep/notify', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'notifyUser'])->name('metrics.sleep.notify');

    Route::get('/metrics/{user}/activities', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'index'])->name('metrics.activities.index');
    Route::post('/metrics/{user}/activities/notify', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'notifyUser'])->name('metrics.activities.notify');
});

require __DIR__.'/auth.php';
