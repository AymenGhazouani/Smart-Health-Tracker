<?php

namespace App\Http\Controllers\PsychologyVisits;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StorePsyNoteRequest;
use App\Http\Requests\PsychologyVisits\UpdatePsyNoteRequest;
use App\Services\PsychologyVisits\PsyNoteService;
use App\Services\PsychologyVisits\PsySessionService;
use Illuminate\Http\Request;

class AdminPsyNoteController extends Controller
{
    private PsyNoteService $noteService;
    private PsySessionService $sessionService;

    public function __construct(PsyNoteService $noteService, PsySessionService $sessionService)
    {
        $this->noteService = $noteService;
        $this->sessionService = $sessionService;
    }

    /**
     * Show the form for creating a new note
     */
    public function create(int $psy_session)
    {
        try {
            $session = $this->sessionService->getSessionById($psy_session);
            $session->load(['psychologist', 'patient']);
            
            return view('admin.psy-sessions.notes.create', compact('session'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-sessions.index')
                ->with('error', 'Session not found.');
        }
    }

    /**
     * Store a newly created note
     */
    public function store(StorePsyNoteRequest $request, int $psy_session)
    {
        try {
            // Debug: Log the request
            \Log::info('Note creation request', [
                'session_id' => $psy_session,
                'request_data' => $request->all(),
                'user_id' => auth()->id()
            ]);
            
            // Get the session to find the psychologist_id
            $session = $this->sessionService->getSessionById($psy_session);
            $psychologistId = $session->psychologist_id;
            
            $data = $request->validated();
            $data['psy_session_id'] = $psy_session;
            $data['psychologist_id'] = $psychologistId;
            
            // Debug: Log the processed data
            \Log::info('Processed note data', $data);

            $note = $this->noteService->createNote($data);
            
            return redirect()->route('admin.psy-sessions.show', $psy_session)
                ->with('success', 'Note added successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Note creation failed', [
                'error' => $e->getMessage(),
                'session_id' => $psy_session,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to add note: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified note
     */
    public function edit(int $psy_session, int $note)
    {
        try {
            $session = $this->sessionService->getSessionById($psy_session);
            $session->load(['psychologist', 'patient']);
            
            $psychologistId = $session->psychologist_id;
            $note = $this->noteService->getNoteById($note, $psychologistId);
            
            return view('admin.psy-sessions.notes.edit', compact('session', 'note'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-sessions.show', $psy_session)
                ->with('error', 'Note not found or access denied.');
        }
    }

    /**
     * Update the specified note
     */
    public function update(UpdatePsyNoteRequest $request, int $psy_session, int $note)
    {
        try {
            // Get the session to find the psychologist_id
            $session = $this->sessionService->getSessionById($psy_session);
            $psychologistId = $session->psychologist_id;
            $note = $this->noteService->updateNote($note, $request->validated(), $psychologistId);
            
            return redirect()->route('admin.psy-sessions.show', $psy_session)
                ->with('success', 'Note updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update note: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified note
     */
    public function destroy(Request $request, int $psy_session, int $note)
    {
        try {
            // Get the session to find the psychologist_id
            $session = $this->sessionService->getSessionById($psy_session);
            $psychologistId = $session->psychologist_id;
            $this->noteService->deleteNote($note, $psychologistId);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Note deleted successfully'
                ]);
            }
            
            return redirect()->route('admin.psy-sessions.show', $psy_session)
                ->with('success', 'Note deleted successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.psy-sessions.show', $psy_session)
                ->with('error', 'Failed to delete note: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of notes for a psychologist
     */
    public function index(Request $request)
    {
        try {
            $psychologistId = auth()->user()->id;
            $filters = $request->only(['note_type', 'session_id', 'start_date', 'end_date']);
            
            $notes = $this->noteService->getPsychologistNotes($psychologistId, $filters);
            $notes->load(['session', 'session.patient']);
            
            return view('admin.psy-notes.index', compact('notes'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load notes: ' . $e->getMessage());
        }
    }

    /**
     * Search notes
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'q' => 'required|string|min:2|max:100'
            ]);

            $psychologistId = auth()->user()->id;
            $searchTerm = $request->input('q');
            $notes = $this->noteService->searchNotes($psychologistId, $searchTerm);
            $notes->load(['session', 'session.patient']);
            
            return view('admin.psy-notes.search', compact('notes', 'searchTerm'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-notes.index')
                ->with('error', 'Search failed: ' . $e->getMessage());
        }
    }

    /**
     * Get note statistics
     */
    public function statistics(Request $request)
    {
        try {
            $psychologistId = auth()->user()->id;
            $statistics = $this->noteService->getNoteStatistics($psychologistId);
            
            return view('admin.psy-notes.statistics', compact('statistics'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-notes.index')
                ->with('error', 'Failed to load statistics: ' . $e->getMessage());
        }
    }

    /**
     * Export notes
     */
    public function export(Request $request)
    {
        try {
            $psychologistId = auth()->user()->id;
            $filters = $request->only(['note_type', 'session_id', 'start_date', 'end_date']);
            
            $exportData = $this->noteService->exportPsychologistNotes($psychologistId, $filters);
            
            // Generate CSV or JSON export
            $filename = 'psychology_notes_export_' . now()->format('Y-m-d_H-i-s') . '.json';
            
            return response()->json($exportData)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.psy-notes.index')
                ->with('error', 'Export failed: ' . $e->getMessage());
        }
    }
}
