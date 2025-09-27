<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
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
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Protected routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Specialties CRUD
    Route::resource('specialties', SpecialtyController::class);

    // Doctors CRUD
    Route::resource('doctors', DoctorController::class);

    // Reviews CRUD
    Route::resource('reviews', DoctorReviewController::class);
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    

    Route::get('/admin/doctor', [DoctorController::class, 'index'])->name('doctor.index');
    Route::get('/admin/doctor/create', [DoctorController::class, 'create'])->name('doctor.create');
    Route::post('/admin/doctor', [DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/admin/doctor/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::put('/admin/doctor/{doctor}', [DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/admin/doctor/{doctor}', [DoctorController::class, 'destroy'])->name('doctor.destroy');

    
    // Optionally: specialty and review CRUD for admin
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('review', DoctorReviewController::class)->only(['index', 'destroy']);
});

require __DIR__.'/auth.php';
