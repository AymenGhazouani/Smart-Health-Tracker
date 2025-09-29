<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AvailabilitySlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VisitSummaryController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\SpecialtyController;
use App\Http\Controllers\Doctor\DoctorReviewController;

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
        return redirect()->route('psychology.dashboard');
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

    // ✅ Client meal planning routes
    Route::prefix('meals')->name('client.meals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\MealPlanController::class, 'index'])->name('index');
        Route::get('/foods', [\App\Http\Controllers\Client\MealPlanController::class, 'foods'])->name('foods');
        Route::get('/create', [\App\Http\Controllers\Client\MealPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Client\MealPlanController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Client\MealPlanController::class, 'show'])->name('show');
    });

    // ✅ Client psychology visits routes
    Route::prefix('psychology')->name('psychology.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'dashboard'])->name('dashboard');

        // Psychologists
        Route::get('/psychologists', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'psychologists'])->name('psychologists');
        Route::get('/psychologists/{id}', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'showPsychologist'])->name('psychologists.show');

        // Sessions
        Route::get('/sessions', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'sessions'])->name('sessions');
        Route::get('/sessions/{id}', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'showSession'])->name('sessions.show');
        Route::post('/sessions/{id}/cancel', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'cancelSession'])->name('sessions.cancel');

        // Booking
        Route::get('/book-session', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'bookSession'])->name('book-session');
        Route::post('/sessions', [\App\Http\Controllers\PsychologyVisits\ClientPsychologyController::class, 'storeSession'])->name('sessions.store');
    });

    // Specialties CRUD
    Route::resource('specialties', SpecialtyController::class);

    // Doctors CRUD
    Route::resource('doctors', DoctorController::class);

    // Reviews CRUD
    Route::resource('reviews', DoctorReviewController::class);
});

// Optionally: specialty and review CRUD for admin
Route::get('specialties', [SpecialtyController::class, 'index'])->name('specialties.index');
Route::resource('review', DoctorReviewController::class)->only(['index', 'destroy']);

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);

    // Admin specific provider management
    Route::resource('providers', ProviderController::class);
    Route::get('/appointments', [AppointmentController::class, 'adminIndex'])->name('appointments.index');

    Route::resource('meals', \App\Http\Controllers\MealPlanningFt\Api\V1\MealController::class);
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

    Route::get('/admin/doctor', [DoctorController::class, 'index'])->name('doctor.index');
    Route::get('/admin/doctor/create', [DoctorController::class, 'create'])->name('doctor.create');
    Route::post('/admin/doctor', [DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/admin/doctor/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::put('/admin/doctor/{doctor}', [DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/admin/doctor/{doctor}', [DoctorController::class, 'destroy'])->name('doctor.destroy');
    Route::get('/admin/doctor/{doctor}/show', [DoctorController::class, 'showAdmin'])->name('doctor.showAdmin');

    // Notes Management (standalone)
    Route::get('psy-notes', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'index'])->name('psy-notes.index');
    Route::get('psy-notes/search', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'search'])->name('psy-notes.search');
    Route::get('psy-notes/statistics', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'statistics'])->name('psy-notes.statistics');
    Route::get('psy-notes/export', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'export'])->name('psy-notes.export');
});

require __DIR__.'/auth.php';
