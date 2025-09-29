<?php

namespace App\Services\PsychologyVisits;

use App\Models\PsyNote;
use App\Models\PsySession;
use App\Models\Psychologist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PsyNoteService
{
    /**
     * Get all notes for a session
     */
    public function getSessionNotes(int $sessionId, int $psychologistId): Collection
    {
        return PsyNote::where('psy_session_id', $sessionId)
            ->where('psychologist_id', $psychologistId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get note by ID (with access control)
     */
    public function getNoteById(int $noteId, int $psychologistId): PsyNote
    {
        $note = PsyNote::findOrFail($noteId);
        
        if (!$note->canBeAccessedBy($psychologistId)) {
            throw new \Exception('You do not have permission to access this note.');
        }

        return $note;
    }

    /**
     * Create a new note
     */
    public function createNote(array $data): PsyNote
    {
        return DB::transaction(function () use ($data) {
            // Verify session exists and psychologist has access
            $session = PsySession::findOrFail($data['psy_session_id']);
            
            if ($session->psychologist_id != $data['psychologist_id']) {
                throw new \Exception('You can only add notes to your own sessions.');
            }

            // Set default note type if not provided
            if (!isset($data['note_type'])) {
                $data['note_type'] = 'session_notes';
            }

            // Ensure encryption is enabled for sensitive notes
            if (!isset($data['is_encrypted'])) {
                $data['is_encrypted'] = true;
            }

            return PsyNote::create($data);
        });
    }

    /**
     * Update note (with access control)
     */
    public function updateNote(int $noteId, array $data, int $psychologistId): PsyNote
    {
        return DB::transaction(function () use ($noteId, $data, $psychologistId) {
            $note = PsyNote::findOrFail($noteId);
            
            if (!$note->canBeAccessedBy($psychologistId)) {
                throw new \Exception('You do not have permission to update this note.');
            }

            $note->update($data);
            return $note;
        });
    }

    /**
     * Delete note (with access control)
     */
    public function deleteNote(int $noteId, int $psychologistId): bool
    {
        $note = PsyNote::findOrFail($noteId);
        
        if (!$note->canBeAccessedBy($psychologistId)) {
            throw new \Exception('You do not have permission to delete this note.');
        }

        return $note->delete();
    }

    /**
     * Get all notes by a psychologist
     */
    public function getPsychologistNotes(int $psychologistId, array $filters = []): Collection
    {
        $query = PsyNote::with(['session', 'session.patient'])
            ->where('psychologist_id', $psychologistId);

        // Filter by note type
        if (isset($filters['note_type'])) {
            $query->byType($filters['note_type']);
        }

        // Filter by session
        if (isset($filters['session_id'])) {
            $query->where('psy_session_id', $filters['session_id']);
        }

        // Filter by date range
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get notes by type
     */
    public function getNotesByType(int $psychologistId, string $noteType): Collection
    {
        return PsyNote::with(['session', 'session.patient'])
            ->where('psychologist_id', $psychologistId)
            ->byType($noteType)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Search notes by content
     */
    public function searchNotes(int $psychologistId, string $searchTerm): Collection
    {
        return PsyNote::with(['session', 'session.patient'])
            ->where('psychologist_id', $psychologistId)
            ->where(function ($query) use ($searchTerm) {
                // Note: This is a simplified search. In production, you might want to use
                // a more sophisticated search solution like Elasticsearch
                $query->where('content', 'like', '%' . $searchTerm . '%')
                      ->orWhere('note_type', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get note statistics for a psychologist
     */
    public function getNoteStatistics(int $psychologistId): array
    {
        $totalNotes = PsyNote::where('psychologist_id', $psychologistId)->count();
        
        $notesByType = PsyNote::where('psychologist_id', $psychologistId)
            ->selectRaw('note_type, COUNT(*) as count')
            ->groupBy('note_type')
            ->pluck('count', 'note_type')
            ->toArray();

        $recentNotes = PsyNote::where('psychologist_id', $psychologistId)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return [
            'total_notes' => $totalNotes,
            'notes_by_type' => $notesByType,
            'recent_notes' => $recentNotes,
            'note_types' => PsyNote::getNoteTypes(),
        ];
    }

    /**
     * Export notes for a psychologist (for backup/transfer purposes)
     */
    public function exportPsychologistNotes(int $psychologistId, array $filters = []): array
    {
        $notes = $this->getPsychologistNotes($psychologistId, $filters);
        
        return $notes->map(function ($note) {
            return [
                'id' => $note->id,
                'session_id' => $note->psy_session_id,
                'patient_name' => $note->session->patient->name ?? 'Unknown',
                'session_date' => $note->session->start_time->format('Y-m-d H:i:s'),
                'note_type' => $note->note_type,
                'content' => $note->getDecryptedContent(),
                'created_at' => $note->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $note->updated_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }
}

