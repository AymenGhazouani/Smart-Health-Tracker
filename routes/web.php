<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Metrics\MetricsDashboardController;
use App\Http\Controllers\Metrics\WeightsDetailController;
use App\Http\Controllers\Metrics\SleepDetailController;
use App\Http\Controllers\Metrics\ActivitiesDetailController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AvailabilitySlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\VisitSummaryController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\SpecialtyController;
use App\Http\Controllers\Doctor\DoctorReviewController;
use App\Http\Controllers\Doctor\StatsController;

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

    Route::get('/doctors/pdf', [DoctorController::class, 'exportPdf'])->name('doctors.pdf');

    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * ==============================
     *  Metrics Routes
     * ==============================
     */
    Route::get('/metrics', [MetricsDashboardController::class, 'index'])->name('metrics.dashboard');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationsController::class, 'markAllAsRead'])->name('notifications.readAll');
    
    // User metrics routes

    // Weights
    Route::get('/metrics/weights', [WeightsDetailController::class, 'index'])->name('metrics.weights');
    Route::get('/metrics/weights/create', [WeightsDetailController::class, 'create'])->name('metrics.weights.create');
    Route::post('/metrics/weights', [WeightsDetailController::class, 'store'])->name('metrics.weights.store');
    Route::get('/metrics/weights/{weight}/edit', [WeightsDetailController::class, 'edit'])->name('metrics.weights.edit');
    Route::put('/metrics/weights/{weight}', [WeightsDetailController::class, 'update'])->name('metrics.weights.update');
    Route::delete('/metrics/weights/{weight}', [WeightsDetailController::class, 'destroy'])->name('metrics.weights.destroy');

    // Sleep
    Route::get('/metrics/sleep', [SleepDetailController::class, 'index'])->name('metrics.sleep');
    Route::get('/metrics/sleep/create', [SleepDetailController::class, 'create'])->name('metrics.sleep.create');
    Route::post('/metrics/sleep', [SleepDetailController::class, 'store'])->name('metrics.sleep.store');
    Route::get('/metrics/sleep/{sleepSession}/edit', [SleepDetailController::class, 'edit'])->name('metrics.sleep.edit');
    Route::put('/metrics/sleep/{sleepSession}', [SleepDetailController::class, 'update'])->name('metrics.sleep.update');
    Route::delete('/metrics/sleep/{sleepSession}', [SleepDetailController::class, 'destroy'])->name('metrics.sleep.destroy');

    // Activities
    Route::get('/metrics/activities', [ActivitiesDetailController::class, 'index'])->name('metrics.activities');
    Route::get('/metrics/activities/create', [ActivitiesDetailController::class, 'create'])->name('metrics.activities.create');
    Route::post('/metrics/activities', [ActivitiesDetailController::class, 'store'])->name('metrics.activities.store');
    Route::get('/metrics/activities/{activity}/edit', [ActivitiesDetailController::class, 'edit'])->name('metrics.activities.edit');
    Route::put('/metrics/activities/{activity}', [ActivitiesDetailController::class, 'update'])->name('metrics.activities.update');
    Route::delete('/metrics/activities/{activity}', [ActivitiesDetailController::class, 'destroy'])->name('metrics.activities.destroy');

    /**
     * ==============================
     *  Provider & Appointments
     * ==============================
     */
    Route::resource('providers', ProviderController::class);
    Route::resource('availability-slots', AvailabilitySlotController::class);

    // Appointments
    Route::get('appointments/get-available-slots', [AppointmentController::class, 'getAvailableSlots'])
        ->name('appointments.get-available-slots');
    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
    Route::resource('appointments', AppointmentController::class);

    // Visit Summaries
    Route::get('appointments/{appointment}/visit-summary/create', [VisitSummaryController::class, 'create'])
        ->name('visit-summaries.create');
    Route::resource('visit-summaries', VisitSummaryController::class)
        ->except(['index', 'create', 'destroy']);

    /**
     * ==============================
     *  Client Meal Planning
     * ==============================
     */
    Route::prefix('meals')->name('client.meals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\MealPlanController::class, 'index'])->name('index');
        Route::get('/foods', [\App\Http\Controllers\Client\MealPlanController::class, 'foods'])->name('foods');
        Route::get('/create', [\App\Http\Controllers\Client\MealPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Client\MealPlanController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Client\MealPlanController::class, 'show'])->name('show');
    });

    /**
     * ==============================
     *  Client Psychology
     * ==============================
     */
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

    /**
     * ==============================
     *  Doctors & Reviews
     * ==============================
     */
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('reviews', DoctorReviewController::class);
});

// Public specialty & review browsing
Route::get('/admin/stats/doctors-per-specialty', [App\Http\Controllers\Doctor\StatsController::class, 'doctorsPerSpecialty'])
    ->middleware(['auth', 'admin'])
    ->name('doctors.stats');

     
Route::get('specialties', [SpecialtyController::class, 'index'])->name('specialties.index');


Route::resource('review', DoctorReviewController::class)->only(['index', 'destroy']);

// Admin-only routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::resource('foods', \App\Http\Controllers\MealPlanningFt\Api\V1\FoodController::class);
    
    /**
     * ==============================
     *  Admin Metrics
     * ==============================
     */
    Route::get('/metrics', [\App\Http\Controllers\Admin\Metrics\AdminMetricsController::class, 'index'])->name('metrics.dashboard');

    // Admin metrics per-user + notify
    Route::get('/metrics/{user}/weights', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'index'])->name('metrics.weights.index');
    Route::post('/metrics/{user}/weights/notify', [\App\Http\Controllers\Admin\Metrics\AdminWeightsController::class, 'notifyUser'])->name('metrics.weights.notify');

    Route::get('/metrics/{user}/sleep', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'index'])->name('metrics.sleep.index');
    Route::post('/metrics/{user}/sleep/notify', [\App\Http\Controllers\Admin\Metrics\AdminSleepController::class, 'notifyUser'])->name('metrics.sleep.notify');

    Route::get('/metrics/{user}/activities', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'index'])->name('metrics.activities.index');
    Route::post('/metrics/{user}/activities/notify', [\App\Http\Controllers\Admin\Metrics\AdminActivitiesController::class, 'notifyUser'])->name('metrics.activities.notify');

    /**
     * ==============================
     *  Admin Providers & Appointments
     * ==============================
     */
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

    // Standalone Notes
    Route::get('psy-notes', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'index'])->name('psy-notes.index');
    Route::get('psy-notes/search', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'search'])->name('psy-notes.search');
    Route::get('psy-notes/statistics', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'statistics'])->name('psy-notes.statistics');
    Route::get('psy-notes/export', [\App\Http\Controllers\PsychologyVisits\AdminPsyNoteController::class, 'export'])->name('psy-notes.export');

    /**
     * ==============================
     *  Admin Doctors
     * ==============================
     */
    Route::get('/doctor', [DoctorController::class, 'index'])->name('doctor.index');
    Route::get('/doctor/create', [DoctorController::class, 'create'])->name('doctor.create');
    Route::post('/doctor', [DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/doctor/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::put('/doctor/{doctor}', [DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/doctor/{doctor}', [DoctorController::class, 'destroy'])->name('doctor.destroy');
    Route::get('/doctor/{doctor}/show', [DoctorController::class, 'showAdmin'])->name('doctor.showAdmin');
    
});

require __DIR__.'/auth.php';

