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
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);
    Route::resource('psychologists', \App\Http\Controllers\PsychologyVisits\AdminPsychologistController::class);
    
    // Psychology Sessions Management
    Route::resource('psy-sessions', \App\Http\Controllers\PsychologyVisits\AdminPsySessionController::class);
    Route::post('psy-sessions/{psy_session}/cancel', [\App\Http\Controllers\PsychologyVisits\AdminPsySessionController::class, 'cancel'])->name('psy-sessions.cancel');
    Route::post('psy-sessions/{psy_session}/reschedule', [\App\Http\Controllers\PsychologyVisits\AdminPsySessionController::class, 'reschedule'])->name('psy-sessions.reschedule');
    Route::post('psy-sessions/{psy_session}/start', [\App\Http\Controllers\PsychologyVisits\AdminPsySessionController::class, 'start'])->name('psy-sessions.start');
    Route::post('psy-sessions/{psy_session}/complete', [\App\Http\Controllers\PsychologyVisits\AdminPsySessionController::class, 'complete'])->name('psy-sessions.complete');
    
    // Psychology Notes Management
    Route::get('psy-sessions/{psy_session}/notes/create', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'create'])->name('psy-sessions.notes.create');
    Route::post('psy-sessions/{psy_session}/notes', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'store'])->name('psy-sessions.notes.store');
    Route::get('psy-sessions/{psy_session}/notes/{note}/edit', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'edit'])->name('psy-sessions.notes.edit');
    Route::put('psy-sessions/{psy_session}/notes/{note}', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'update'])->name('psy-sessions.notes.update');
    Route::delete('psy-sessions/{psy_session}/notes/{note}', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'destroy'])->name('psy-sessions.notes.destroy');
    
    // Notes Management (standalone)
    Route::get('psy-notes', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'index'])->name('psy-notes.index');
    Route::get('psy-notes/search', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'search'])->name('psy-notes.search');
    Route::get('psy-notes/statistics', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'statistics'])->name('psy-notes.statistics');
    Route::get('psy-notes/export', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'export'])->name('psy-notes.export');
});

require __DIR__.'/auth.php';
