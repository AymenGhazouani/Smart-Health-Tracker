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
    Route::get('/metrics/weights', [WeightsDetailController::class, 'index'])->name('metrics.weights');
    Route::get('/metrics/sleep', [SleepDetailController::class, 'index'])->name('metrics.sleep');
    Route::get('/metrics/activities', [ActivitiesDetailController::class, 'index'])->name('metrics.activities');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);

    // Admin metrics dashboard
    Route::get('/metrics', [\App\Http\Controllers\Admin\Metrics\AdminMetricsController::class, 'index'])->name('metrics.dashboard');

    // Admin metrics CRUD per selected user
    Route::get('/metrics/{user}/weights', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'index'])->name('metrics.weights.index');
    Route::get('/metrics/{user}/weights/create', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'create'])->name('metrics.weights.create');
    Route::post('/metrics/{user}/weights', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'store'])->name('metrics.weights.store');
    Route::get('/metrics/{user}/weights/{weight}/edit', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'edit'])->name('metrics.weights.edit');
    Route::put('/metrics/{user}/weights/{weight}', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'update'])->name('metrics.weights.update');
    Route::delete('/metrics/{user}/weights/{weight}', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'destroy'])->name('metrics.weights.destroy');

    Route::get('/metrics/{user}/sleep', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'index'])->name('metrics.sleep.index');
    Route::get('/metrics/{user}/sleep/create', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'create'])->name('metrics.sleep.create');
    Route::post('/metrics/{user}/sleep', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'store'])->name('metrics.sleep.store');
    Route::get('/metrics/{user}/sleep/{sleepSession}/edit', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'edit'])->name('metrics.sleep.edit');
    Route::put('/metrics/{user}/sleep/{sleepSession}', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'update'])->name('metrics.sleep.update');
    Route::delete('/metrics/{user}/sleep/{sleepSession}', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'destroy'])->name('metrics.sleep.destroy');

    Route::get('/metrics/{user}/activities', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'index'])->name('metrics.activities.index');
    Route::get('/metrics/{user}/activities/create', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'create'])->name('metrics.activities.create');
    Route::post('/metrics/{user}/activities', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'store'])->name('metrics.activities.store');
    Route::get('/metrics/{user}/activities/{activity}/edit', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'edit'])->name('metrics.activities.edit');
    Route::put('/metrics/{user}/activities/{activity}', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'update'])->name('metrics.activities.update');
    Route::delete('/metrics/{user}/activities/{activity}', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'destroy'])->name('metrics.activities.destroy');
});

require __DIR__.'/auth.php';
