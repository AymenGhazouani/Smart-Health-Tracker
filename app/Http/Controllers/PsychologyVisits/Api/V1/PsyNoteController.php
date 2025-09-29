<?php

namespace App\Http\Controllers\PsychologyVisits\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StorePsyNoteRequest;
use App\Http\Requests\PsychologyVisits\UpdatePsyNoteRequest;
use App\Services\PsychologyVisits\PsyNoteService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PsyNoteController extends Controller
{
    private PsyNoteService $noteService;

    public function __construct(PsyNoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of notes for a session
     * 
     * GET /api/v1/psy-sessions/{sessionId}/notes
     */
    public function index(Request $request, int $sessionId): JsonResponse
    {
        try {
            // Get psychologist ID from authenticated user
            $psychologistId = $request->user()->id; // Assuming user is psychologist
            
            $notes = $this->noteService->getSessionNotes($sessionId, $psychologistId);

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Session notes retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve session notes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created note for a session
     * 
     * POST /api/v1/psy-sessions/{sessionId}/notes
     */
    public function store(StorePsyNoteRequest $request, int $sessionId): JsonResponse
    {
        try {
            // Get psychologist ID from authenticated user
            $psychologistId = $request->user()->id; // Assuming user is psychologist
            
            $data = $request->validated();
            $data['psy_session_id'] = $sessionId;
            $data['psychologist_id'] = $psychologistId;

            $note = $this->noteService->createNote($data);

            return response()->json([
                'success' => true,
                'data' => $note,
                'message' => 'Note created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified note
     * 
     * GET /api/v1/psy-sessions/{sessionId}/notes/{noteId}
     */
    public function show(Request $request, int $sessionId, int $noteId): JsonResponse
    {
        try {
            // Get psychologist ID from authenticated user
            $psychologistId = $request->user()->id; // Assuming user is psychologist
            
            $note = $this->noteService->getNoteById($noteId, $psychologistId);

            return response()->json([
                'success' => true,
                'data' => $note,
                'message' => 'Note retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified note
     * 
     * PUT /api/v1/psy-sessions/{sessionId}/notes/{noteId}
     */
    public function update(UpdatePsyNoteRequest $request, int $sessionId, int $noteId): JsonResponse
    {
        try {
            // Get psychologist ID from authenticated user
            $psychologistId = $request->user()->id; // Assuming user is psychologist
            
            $note = $this->noteService->updateNote($noteId, $request->validated(), $psychologistId);

            return response()->json([
                'success' => true,
                'data' => $note,
                'message' => 'Note updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified note
     * 
     * DELETE /api/v1/psy-sessions/{sessionId}/notes/{noteId}
     */
    public function destroy(Request $request, int $sessionId, int $noteId): JsonResponse
    {
        try {
            // Get psychologist ID from authenticated user
            $psychologistId = $request->user()->id; // Assuming user is psychologist
            
            $this->noteService->deleteNote($noteId, $psychologistId);

            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete note',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all notes by a psychologist
     * 
     * GET /api/v1/psychologists/{psychologistId}/notes
     */
    public function getPsychologistNotes(Request $request, int $psychologistId): JsonResponse
    {
        try {
            // Verify the authenticated user is the psychologist
            if ($request->user()->id !== $psychologistId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to psychologist notes'
                ], 403);
            }

            $filters = $request->only(['note_type', 'session_id', 'start_date', 'end_date']);
            $notes = $this->noteService->getPsychologistNotes($psychologistId, $filters);

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Psychologist notes retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve psychologist notes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search notes by content
     * 
     * GET /api/v1/psychologists/{psychologistId}/notes/search?q=search_term
     */
    public function searchNotes(Request $request, int $psychologistId): JsonResponse
    {
        try {
            // Verify the authenticated user is the psychologist
            if ($request->user()->id !== $psychologistId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to psychologist notes'
                ], 403);
            }

            $request->validate([
                'q' => 'required|string|min:2|max:100'
            ]);

            $searchTerm = $request->input('q');
            $notes = $this->noteService->searchNotes($psychologistId, $searchTerm);

            return response()->json([
                'success' => true,
                'data' => $notes,
                'message' => 'Notes search completed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search notes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get note statistics for a psychologist
     * 
     * GET /api/v1/psychologists/{psychologistId}/notes/statistics
     */
    public function getNoteStatistics(Request $request, int $psychologistId): JsonResponse
    {
        try {
            // Verify the authenticated user is the psychologist
            if ($request->user()->id !== $psychologistId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to psychologist statistics'
                ], 403);
            }

            $statistics = $this->noteService->getNoteStatistics($psychologistId);

            return response()->json([
                'success' => true,
                'data' => $statistics,
                'message' => 'Note statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve note statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export notes for a psychologist
     * 
     * GET /api/v1/psychologists/{psychologistId}/notes/export
     */
    public function exportNotes(Request $request, int $psychologistId): JsonResponse
    {
        try {
            // Verify the authenticated user is the psychologist
            if ($request->user()->id !== $psychologistId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to psychologist notes'
                ], 403);
            }

            $filters = $request->only(['note_type', 'session_id', 'start_date', 'end_date']);
            $exportData = $this->noteService->exportPsychologistNotes($psychologistId, $filters);

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'message' => 'Notes exported successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export notes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

