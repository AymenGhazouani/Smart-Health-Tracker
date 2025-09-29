<?php

namespace App\Services\PsychologyVisits;

use App\Models\PsySession;
use App\Models\Psychologist;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PsySessionService
{
    /**
     * Get all sessions with optional filters
     */
    public function getAllSessions(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = PsySession::with(['psychologist', 'patient']);

        // Filter by psychologist
        if (isset($filters['psychologist_id'])) {
            $query->byPsychologist($filters['psychologist_id']);
        }

        // Filter by patient
        if (isset($filters['patient_id'])) {
            $query->byPatient($filters['patient_id']);
        }

        // Filter by status
        if (isset($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        // Filter by date range
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        // Filter upcoming sessions
        if (isset($filters['upcoming']) && $filters['upcoming']) {
            $query->upcoming();
        }

        // Filter past sessions
        if (isset($filters['past']) && $filters['past']) {
            $query->past();
        }

        return $query->orderBy('start_time', 'desc')->paginate($perPage);
    }

    /**
     * Get session by ID
     */
    public function getSessionById(int $id): PsySession
    {
        return PsySession::with(['psychologist', 'patient', 'notes'])->findOrFail($id);
    }

    /**
     * Create a new session (booking)
     */
    public function createSession(array $data): PsySession
    {
        return DB::transaction(function () use ($data) {
            // Validate availability
            $this->validateSessionAvailability($data);

            // Set default end time if not provided
            if (!isset($data['end_time'])) {
                $data['end_time'] = Carbon::parse($data['start_time'])->addHour()->toDateTimeString();
            }

            // Set session fee if not provided
            if (!isset($data['session_fee'])) {
                $psychologist = Psychologist::findOrFail($data['psychologist_id']);
                $data['session_fee'] = $psychologist->hourly_rate;
            }

            return PsySession::create($data);
        });
    }

    /**
     * Update session
     */
    public function updateSession(int $id, array $data): PsySession
    {
        return DB::transaction(function () use ($id, $data) {
            $session = PsySession::findOrFail($id);

            // If changing time, validate availability
            if (isset($data['start_time']) || isset($data['psychologist_id'])) {
                $updateData = array_merge($session->toArray(), $data);
                $this->validateSessionAvailability($updateData, $id);
            }

            $session->update($data);
            return $session;
        });
    }

    /**
     * Cancel session
     */
    public function cancelSession(int $id, string $reason = null): PsySession
    {
        $session = PsySession::findOrFail($id);

        if (!$session->canBeCancelled()) {
            throw new \Exception('Session cannot be cancelled. Must be cancelled at least 24 hours in advance.');
        }

        $session->update([
            'status' => 'cancelled',
            'notes' => $session->notes . "\n\nCancellation reason: " . ($reason ?? 'No reason provided'),
        ]);

        return $session;
    }

    /**
     * Reschedule session
     */
    public function rescheduleSession(int $id, string $newStartTime, string $newEndTime = null): PsySession
    {
        return DB::transaction(function () use ($id, $newStartTime, $newEndTime) {
            $session = PsySession::findOrFail($id);

            if (!$session->canBeRescheduled()) {
                throw new \Exception('Session cannot be rescheduled. Must be rescheduled at least 48 hours in advance.');
            }

            $newEndTime = $newEndTime ?? Carbon::parse($newStartTime)->addHour()->toDateTimeString();

            // Validate new time availability
            $this->validateSessionAvailability([
                'psychologist_id' => $session->psychologist_id,
                'start_time' => $newStartTime,
                'end_time' => $newEndTime,
            ], $id);

            $session->update([
                'start_time' => $newStartTime,
                'end_time' => $newEndTime,
            ]);

            return $session;
        });
    }

    /**
     * Complete session
     */
    public function completeSession(int $id, string $notes = null): PsySession
    {
        $session = PsySession::findOrFail($id);

        if ($session->status !== 'in_progress') {
            throw new \Exception('Only sessions in progress can be completed.');
        }

        $updateData = ['status' => 'completed'];
        if ($notes) {
            $updateData['notes'] = $session->notes . "\n\nSession completed: " . $notes;
        }

        $session->update($updateData);
        return $session;
    }

    /**
     * Delete session
     */
    public function deleteSession(int $id): bool
    {
        $session = PsySession::findOrFail($id);
        return $session->delete();
    }

    /**
     * Get sessions for a specific patient
     */
    public function getPatientSessions(int $patientId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $filters['patient_id'] = $patientId;
        return $this->getAllSessions($filters, $perPage);
    }

    /**
     * Get sessions for a specific psychologist
     */
    public function getPsychologistSessions(int $psychologistId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $filters['psychologist_id'] = $psychologistId;
        return $this->getAllSessions($filters, $perPage);
    }

    /**
     * Get upcoming sessions for a patient
     */
    public function getUpcomingPatientSessions(int $patientId): Collection
    {
        return PsySession::with(['psychologist'])
            ->byPatient($patientId)
            ->upcoming()
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get upcoming sessions for a psychologist
     */
    public function getUpcomingPsychologistSessions(int $psychologistId): Collection
    {
        return PsySession::with(['patient'])
            ->byPsychologist($psychologistId)
            ->upcoming()
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Validate session availability
     */
    private function validateSessionAvailability(array $data, int $excludeSessionId = null): void
    {
        $psychologistId = $data['psychologist_id'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'] ?? Carbon::parse($startTime)->addHour()->toDateTimeString();

        // Check if psychologist exists and is active
        $psychologist = Psychologist::findOrFail($psychologistId);
        if (!$psychologist->is_active) {
            throw new \Exception('Psychologist is not available for bookings.');
        }

        // Check if psychologist is available at this time
        if (!$psychologist->isAvailableAt($startTime, $endTime)) {
            throw new \Exception('Psychologist is not available at the requested time.');
        }

        // Check for existing bookings (excluding current session if updating)
        $query = PsySession::where('psychologist_id', $psychologistId)
            ->whereIn('status', ['booked', 'confirmed', 'in_progress'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                         ->where('end_time', '>=', $endTime);
                  });
            });

        if ($excludeSessionId) {
            $query->where('id', '!=', $excludeSessionId);
        }

        $conflictingSession = $query->first();

        if ($conflictingSession) {
            throw new \Exception('Time slot is already booked. Please choose a different time.');
        }
    }
}

