<?php

namespace App\Http\Controllers\PsychologyVisits;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StorePsySessionRequest;
use App\Http\Requests\PsychologyVisits\UpdatePsySessionRequest;
use App\Services\PsychologyVisits\PsySessionService;
use App\Services\PsychologyVisits\PsychologistService;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPsySessionController extends Controller
{
    private PsySessionService $sessionService;
    private PsychologistService $psychologistService;

    public function __construct(PsySessionService $sessionService, PsychologistService $psychologistService)
    {
        $this->sessionService = $sessionService;
        $this->psychologistService = $psychologistService;
    }

    /**
     * Display a listing of sessions for admin
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'psychologist_id', 
            'patient_id', 
            'status', 
            'date_range'
        ]);

        // Apply date range filters
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'today':
                    $filters['start_date'] = now()->startOfDay();
                    $filters['end_date'] = now()->endOfDay();
                    break;
                case 'tomorrow':
                    $filters['start_date'] = now()->addDay()->startOfDay();
                    $filters['end_date'] = now()->addDay()->endOfDay();
                    break;
                case 'this_week':
                    $filters['start_date'] = now()->startOfWeek();
                    $filters['end_date'] = now()->endOfWeek();
                    break;
                case 'next_week':
                    $filters['start_date'] = now()->addWeek()->startOfWeek();
                    $filters['end_date'] = now()->addWeek()->endOfWeek();
                    break;
                case 'upcoming':
                    $filters['upcoming'] = true;
                    break;
                case 'past':
                    $filters['past'] = true;
                    break;
            }
            unset($filters['date_range']);
        }

        $sessions = $this->sessionService->getAllSessions($filters);
        $sessions->load(['psychologist', 'patient', 'notes']);

        // Get data for filters
        $psychologists = $this->psychologistService->getAllPsychologists();
        $patients = User::where('role', 'user')->get();

        return view('admin.psy-sessions.index', compact('sessions', 'psychologists', 'patients'));
    }

    /**
     * Show the form for creating a new session
     */
    public function create()
    {
        $psychologists = $this->psychologistService->getAllPsychologists();
        $patients = User::where('role', 'user')->get();

        return view('admin.psy-sessions.create', compact('psychologists', 'patients'));
    }

    /**
     * Store a newly created session
     */
    public function store(StorePsySessionRequest $request)
    {
        try {
            $session = $this->sessionService->createSession($request->validated());
            
            return redirect()->route('admin.psy-sessions.index')
                ->with('success', 'Session booked successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to book session: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified session
     */
    public function show(int $id)
    {
        try {
            $session = $this->sessionService->getSessionById($id);
            $session->load(['psychologist', 'patient', 'notes.psychologist']);
            
            return view('admin.psy-sessions.show', compact('session'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-sessions.index')
                ->with('error', 'Session not found.');
        }
    }

    /**
     * Show the form for editing the specified session
     */
    public function edit(int $id)
    {
        try {
            $session = $this->sessionService->getSessionById($id);
            $psychologists = $this->psychologistService->getAllPsychologists();
            $patients = User::where('role', 'user')->get();
            
            return view('admin.psy-sessions.edit', compact('session', 'psychologists', 'patients'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-sessions.index')
                ->with('error', 'Session not found.');
        }
    }

    /**
     * Update the specified session
     */
    public function update(UpdatePsySessionRequest $request, int $id)
    {
        try {
            $session = $this->sessionService->updateSession($id, $request->validated());
            
            return redirect()->route('admin.psy-sessions.show', $id)
                ->with('success', 'Session updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update session: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified session
     */
    public function destroy(int $id)
    {
        try {
            $this->sessionService->deleteSession($id);
            
            return redirect()->route('admin.psy-sessions.index')
                ->with('success', 'Session deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-sessions.index')
                ->with('error', 'Failed to delete session: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a session
     */
    public function cancel(Request $request, int $id)
    {
        try {
            $reason = $request->input('reason');
            $session = $this->sessionService->cancelSession($id, $reason);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Session cancelled successfully'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Session cancelled successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to cancel session: ' . $e->getMessage());
        }
    }

    /**
     * Reschedule a session
     */
    public function reschedule(Request $request, int $id)
    {
        try {
            $request->validate([
                'new_start_time' => 'required|date|after:now',
                'new_end_time' => 'nullable|date|after:new_start_time'
            ]);

            $newStartTime = $request->input('new_start_time');
            $newEndTime = $request->input('new_end_time');
            
            $session = $this->sessionService->rescheduleSession($id, $newStartTime, $newEndTime);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Session rescheduled successfully'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Session rescheduled successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to reschedule session: ' . $e->getMessage());
        }
    }

    /**
     * Start a session
     */
    public function start(Request $request, int $id)
    {
        try {
            $session = $this->sessionService->updateSession($id, ['status' => 'in_progress']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Session started successfully'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Session started successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to start session: ' . $e->getMessage());
        }
    }

    /**
     * Complete a session
     */
    public function complete(Request $request, int $id)
    {
        try {
            $notes = $request->input('notes');
            $session = $this->sessionService->completeSession($id, $notes);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Session completed successfully'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Session completed successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to complete session: ' . $e->getMessage());
        }
    }
}
