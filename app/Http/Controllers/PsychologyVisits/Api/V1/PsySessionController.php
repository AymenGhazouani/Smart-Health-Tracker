<?php

namespace App\Http\Controllers\PsychologyVisits\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StorePsySessionRequest;
use App\Http\Requests\PsychologyVisits\UpdatePsySessionRequest;
use App\Services\PsychologyVisits\PsySessionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PsySessionController extends Controller
{
    private PsySessionService $sessionService;

    public function __construct(PsySessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
     * Display a listing of sessions with filters
     * 
     * Query parameters:
     * - psychologist_id: Filter by psychologist
     * - patient_id: Filter by patient
     * - status: Filter by status (booked, confirmed, completed, etc.)
     * - start_date: Filter sessions from this date
     * - end_date: Filter sessions until this date
     * - upcoming: Get only upcoming sessions
     * - past: Get only past sessions
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'psychologist_id', 
                'patient_id', 
                'status', 
                'start_date', 
                'end_date',
                'upcoming',
                'past'
            ]);

            $sessions = $this->sessionService->getAllSessions($filters);

            return response()->json([
                'success' => true,
                'data' => $sessions,
                'message' => 'Sessions retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sessions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created session (booking)
     */
    public function store(StorePsySessionRequest $request): JsonResponse
    {
        try {
            $session = $this->sessionService->createSession($request->validated());

            return response()->json([
                'success' => true,
                'data' => $session,
                'message' => 'Session booked successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to book session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified session
     */
    public function show(int $id): JsonResponse
    {
        try {
            $session = $this->sessionService->getSessionById($id);

            return response()->json([
                'success' => true,
                'data' => $session,
                'message' => 'Session retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified session
     */
    public function update(UpdatePsySessionRequest $request, int $id): JsonResponse
    {
        try {
            $session = $this->sessionService->updateSession($id, $request->validated());

            return response()->json([
                'success' => true,
                'data' => $session,
                'message' => 'Session updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified session
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->sessionService->deleteSession($id);

            return response()->json([
                'success' => true,
                'message' => 'Session deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a session
     * 
     * POST /api/v1/psy-sessions/{id}/cancel
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'reason' => 'nullable|string|max:500'
            ]);

            $reason = $request->input('reason');
            $session = $this->sessionService->cancelSession($id, $reason);

            return response()->json([
                'success' => true,
                'data' => $session,
                'message' => 'Session cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reschedule a session
     * 
     * POST /api/v1/psy-sessions/{id}/reschedule
     */
    public function reschedule(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'new_start_time' => 'required|date|after:now',
                'new_end_time' => 'nullable|date|after:new_start_time'
            ]);

            $newStartTime = $request->input('new_start_time');
            $newEndTime = $request->input('new_end_time');
            
            $session = $this->sessionService->rescheduleSession($id, $newStartTime, $newEndTime);

            return response()->json([
                'success' => true,
                'data' => $session,
                'message' => 'Session rescheduled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reschedule session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete a session
     * 
     * POST /api/v1/psy-sessions/{id}/complete
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:1000'
            ]);

            $notes = $request->input('notes');
            $session = $this->sessionService->completeSession($id, $notes);

            return response()->json([
                'success' => true,
                'data' => $session,
                'message' => 'Session completed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sessions for a specific patient
     * 
     * GET /api/v1/psy-sessions/patient/{patientId}
     */
    public function getPatientSessions(Request $request, int $patientId): JsonResponse
    {
        try {
            $filters = $request->only(['status', 'start_date', 'end_date', 'upcoming', 'past']);
            $sessions = $this->sessionService->getPatientSessions($patientId, $filters);

            return response()->json([
                'success' => true,
                'data' => $sessions,
                'message' => 'Patient sessions retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve patient sessions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sessions for a specific psychologist
     * 
     * GET /api/v1/psy-sessions/psychologist/{psychologistId}
     */
    public function getPsychologistSessions(Request $request, int $psychologistId): JsonResponse
    {
        try {
            $filters = $request->only(['status', 'start_date', 'end_date', 'upcoming', 'past']);
            $sessions = $this->sessionService->getPsychologistSessions($psychologistId, $filters);

            return response()->json([
                'success' => true,
                'data' => $sessions,
                'message' => 'Psychologist sessions retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve psychologist sessions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming sessions for a patient
     * 
     * GET /api/v1/psy-sessions/patient/{patientId}/upcoming
     */
    public function getUpcomingPatientSessions(int $patientId): JsonResponse
    {
        try {
            $sessions = $this->sessionService->getUpcomingPatientSessions($patientId);

            return response()->json([
                'success' => true,
                'data' => $sessions,
                'message' => 'Upcoming patient sessions retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve upcoming patient sessions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming sessions for a psychologist
     * 
     * GET /api/v1/psy-sessions/psychologist/{psychologistId}/upcoming
     */
    public function getUpcomingPsychologistSessions(int $psychologistId): JsonResponse
    {
        try {
            $sessions = $this->sessionService->getUpcomingPsychologistSessions($psychologistId);

            return response()->json([
                'success' => true,
                'data' => $sessions,
                'message' => 'Upcoming psychologist sessions retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve upcoming psychologist sessions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

