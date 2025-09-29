<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PsychologyVisits\Api\V1\PsychologistController;
use App\Http\Controllers\PsychologyVisits\Api\V1\PsySessionController;
use App\Http\Controllers\PsychologyVisits\Api\V1\PsyNoteController;

/*
|--------------------------------------------------------------------------
| Psychology Visits Routes
|--------------------------------------------------------------------------
|
| Routes for the Psychology Visits feature (Psychologists, Sessions, Notes).
| These are prefixed with /api/v1 for versioning.
|
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Psychologist routes
    Route::apiResource('psychologists', PsychologistController::class);
    
    // Additional psychologist routes
    Route::get('psychologists/{id}/availability', [PsychologistController::class, 'availability'])
        ->name('psychologists.availability');
    Route::post('psychologists/{id}/check-availability', [PsychologistController::class, 'checkAvailability'])
        ->name('psychologists.check-availability');

    // PsySession routes
    Route::apiResource('psy-sessions', PsySessionController::class);
    
    // Additional session routes
    Route::post('psy-sessions/{id}/cancel', [PsySessionController::class, 'cancel'])
        ->name('psy-sessions.cancel');
    Route::post('psy-sessions/{id}/reschedule', [PsySessionController::class, 'reschedule'])
        ->name('psy-sessions.reschedule');
    Route::post('psy-sessions/{id}/complete', [PsySessionController::class, 'complete'])
        ->name('psy-sessions.complete');
    
    // Patient-specific session routes
    Route::get('psy-sessions/patient/{patientId}', [PsySessionController::class, 'getPatientSessions'])
        ->name('psy-sessions.patient');
    Route::get('psy-sessions/patient/{patientId}/upcoming', [PsySessionController::class, 'getUpcomingPatientSessions'])
        ->name('psy-sessions.patient.upcoming');
    
    // Psychologist-specific session routes
    Route::get('psy-sessions/psychologist/{psychologistId}', [PsySessionController::class, 'getPsychologistSessions'])
        ->name('psy-sessions.psychologist');
    Route::get('psy-sessions/psychologist/{psychologistId}/upcoming', [PsySessionController::class, 'getUpcomingPsychologistSessions'])
        ->name('psy-sessions.psychologist.upcoming');

    // PsyNote routes (nested under sessions)
    Route::get('psy-sessions/{sessionId}/notes', [PsyNoteController::class, 'index'])
        ->name('psy-sessions.notes.index');
    Route::post('psy-sessions/{sessionId}/notes', [PsyNoteController::class, 'store'])
        ->name('psy-sessions.notes.store');
    Route::get('psy-sessions/{sessionId}/notes/{noteId}', [PsyNoteController::class, 'show'])
        ->name('psy-sessions.notes.show');
    Route::put('psy-sessions/{sessionId}/notes/{noteId}', [PsyNoteController::class, 'update'])
        ->name('psy-sessions.notes.update');
    Route::delete('psy-sessions/{sessionId}/notes/{noteId}', [PsyNoteController::class, 'destroy'])
        ->name('psy-sessions.notes.destroy');
    
    // Additional note routes
    Route::get('psychologists/{psychologistId}/notes', [PsyNoteController::class, 'getPsychologistNotes'])
        ->name('psychologists.notes');
    Route::get('psychologists/{psychologistId}/notes/search', [PsyNoteController::class, 'searchNotes'])
        ->name('psychologists.notes.search');
    Route::get('psychologists/{psychologistId}/notes/statistics', [PsyNoteController::class, 'getNoteStatistics'])
        ->name('psychologists.notes.statistics');
    Route::get('psychologists/{psychologistId}/notes/export', [PsyNoteController::class, 'exportNotes'])
        ->name('psychologists.notes.export');
});

