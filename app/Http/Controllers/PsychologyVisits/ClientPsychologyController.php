<?php

namespace App\Http\Controllers\PsychologyVisits;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StoreSessionRequest;
use App\Services\PsychologyVisits\PsychologistService;
use App\Services\PsychologyVisits\PsySessionService;
use Illuminate\Http\Request;

class ClientPsychologyController extends Controller
{
    private PsychologistService $psychologistService;
    private PsySessionService $sessionService;

    public function __construct(PsychologistService $psychologistService, PsySessionService $sessionService)
    {
        $this->psychologistService = $psychologistService;
        $this->sessionService = $sessionService;
    }

    /**
     * Show client dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get user's sessions
        $userSessions = $this->sessionService->getAllSessions(['patient_id' => $user->id]);
        $userSessions->load(['psychologist']);
        
        // Get upcoming sessions (next 7 days)
        $upcomingSessions = $userSessions->whereIn('status', ['booked', 'confirmed'])
            ->where('start_time', '>', now())
            ->where('start_time', '<=', now()->addDays(7))
            ->take(5);
        
        // Get recent sessions (last 5)
        $recentSessions = $userSessions->sortByDesc('updated_at')->take(5);

        return view('psychology.dashboard', compact('userSessions', 'upcomingSessions', 'recentSessions'));
    }

    /**
     * Show psychologists listing
     */
    public function psychologists(Request $request)
    {
        $filters = $request->only(['specialty', 'availability', 'price_range']);
        
        // Apply price range filter
        if (isset($filters['price_range'])) {
            switch ($filters['price_range']) {
                case '0-50':
                    $filters['min_price'] = 0;
                    $filters['max_price'] = 50;
                    break;
                case '50-100':
                    $filters['min_price'] = 50;
                    $filters['max_price'] = 100;
                    break;
                case '100-150':
                    $filters['min_price'] = 100;
                    $filters['max_price'] = 150;
                    break;
                case '150+':
                    $filters['min_price'] = 150;
                    break;
            }
            unset($filters['price_range']);
        }

        $psychologists = $this->psychologistService->getAllPsychologists($filters);
        
        // Filter by availability if specified
        if (isset($filters['availability'])) {
            $psychologists = $psychologists->filter(function ($psychologist) use ($filters) {
                if (!$psychologist->availability) return false;
                
                switch ($filters['availability']) {
                    case 'today':
                        $day = strtolower(now()->format('l'));
                        return isset($psychologist->availability[$day]) && !empty($psychologist->availability[$day]);
                    case 'tomorrow':
                        $day = strtolower(now()->addDay()->format('l'));
                        return isset($psychologist->availability[$day]) && !empty($psychologist->availability[$day]);
                    case 'this_week':
                        return collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                            ->some(function ($day) use ($psychologist) {
                                return isset($psychologist->availability[$day]) && !empty($psychologist->availability[$day]);
                            });
                    case 'next_week':
                        return collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                            ->some(function ($day) use ($psychologist) {
                                return isset($psychologist->availability[$day]) && !empty($psychologist->availability[$day]);
                            });
                }
                return true;
            });
        }

        return view('psychology.psychologists.index', compact('psychologists'));
    }

    /**
     * Show psychologist profile
     */
    public function showPsychologist(int $id)
    {
        try {
            $psychologist = $this->psychologistService->getPsychologistById($id);
            return view('psychology.psychologists.show', compact('psychologist'));
        } catch (\Exception $e) {
            return redirect()->route('psychology.psychologists')
                ->with('error', 'Psychologist not found.');
        }
    }

    /**
     * Show session booking form
     */
    public function bookSession(Request $request)
    {
        $psychologists = $this->psychologistService->getAllPsychologists();
        
        // If a specific psychologist is requested, pre-select them
        $selectedPsychologist = null;
        if ($request->has('psychologist')) {
            try {
                $selectedPsychologist = $this->psychologistService->getPsychologistById($request->psychologist);
            } catch (\Exception $e) {
                // Psychologist not found, continue with all psychologists
            }
        }

        return view('psychology.sessions.create', compact('psychologists', 'selectedPsychologist'));
    }

    /**
     * Store new session booking
     */
    public function storeSession(StoreSessionRequest $request)
    {
        try {
            // Combine date and time
            $startTime = \Carbon\Carbon::parse($request->session_date . ' ' . $request->session_time);
            $duration = $request->duration ?? 60;
            $endTime = $startTime->copy()->addMinutes($duration);

            $sessionData = [
                'psychologist_id' => $request->psychologist_id,
                'patient_id' => auth()->id(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'booked',
                'notes' => $request->reason,
            ];

            $session = $this->sessionService->createSession($sessionData);

            return redirect()->route('psychology.sessions.show', $session->id)
                ->with('success', 'Séance réservée avec succès ! Vous recevrez un email de confirmation sous peu.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Échec de la réservation de la séance : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show user's sessions
     */
    public function sessions(Request $request)
    {
        $filters = $request->only(['status', 'date_range']);
        $filters['patient_id'] = auth()->id();

        // Apply date range filters
        if (isset($filters['date_range'])) {
            switch ($filters['date_range']) {
                case 'upcoming':
                    $filters['start_date'] = now();
                    break;
                case 'past':
                    $filters['end_date'] = now();
                    break;
                case 'this_month':
                    $filters['start_date'] = now()->startOfMonth();
                    $filters['end_date'] = now()->endOfMonth();
                    break;
                case 'last_month':
                    $filters['start_date'] = now()->subMonth()->startOfMonth();
                    $filters['end_date'] = now()->subMonth()->endOfMonth();
                    break;
            }
            unset($filters['date_range']);
        }

        $sessions = $this->sessionService->getAllSessions($filters);
        $sessions->load(['psychologist']);

        return view('psychology.sessions.index', compact('sessions'));
    }

    /**
     * Show session details
     */
    public function showSession(int $id)
    {
        try {
            $session = $this->sessionService->getSessionById($id);
            
            // Ensure user can only view their own sessions
            if ($session->patient_id !== auth()->id()) {
                return redirect()->route('psychology.sessions')
                    ->with('error', 'You are not authorized to view this session.');
            }

            $session->load(['psychologist']);
            return view('psychology.sessions.show', compact('session'));

        } catch (\Exception $e) {
            return redirect()->route('psychology.sessions')
                ->with('error', 'Session not found.');
        }
    }

    /**
     * Cancel a session
     */
    public function cancelSession(Request $request, int $id)
    {
        try {
            $session = $this->sessionService->getSessionById($id);
            
            // Ensure user can only cancel their own sessions
            if ($session->patient_id !== auth()->id()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to cancel this session.'
                    ], 403);
                }
                return redirect()->route('psychology.sessions')
                    ->with('error', 'You are not authorized to cancel this session.');
            }

            $reason = $request->input('reason', 'No reason provided');
            $this->sessionService->cancelSession($id, $reason);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Session cancelled successfully'
                ]);
            }

            return redirect()->route('psychology.sessions')
                ->with('success', 'Session cancelled successfully!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->route('psychology.sessions')
                ->with('error', 'Failed to cancel session: ' . $e->getMessage());
        }
    }
}
