<?php

namespace App\Services\PsychologyVisits;

use App\Models\Psychologist;
use App\Models\PsySession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PsychologistService
{
    /**
     * Get all psychologists with optional filters
     */
    public function getAllPsychologists(array $filters = []): Collection
    {
        $query = Psychologist::active();

        // Filter by specialty
        if (isset($filters['specialty'])) {
            $query->bySpecialty($filters['specialty']);
        }

        // Filter by availability date
        if (isset($filters['date'])) {
            $query->whereHas('sessions', function ($q) use ($filters) {
                $q->whereDate('start_time', '!=', $filters['date'])
                  ->orWhereNull('start_time');
            });
        }

        // Filter by time slot
        if (isset($filters['time'])) {
            $query->where(function ($q) use ($filters) {
                $timeSlot = $this->getTimeSlotRange($filters['time']);
                $q->whereJsonContains('availability', $timeSlot);
            });
        }

        return $query->with(['sessions' => function ($q) {
            $q->whereIn('status', ['booked', 'confirmed', 'in_progress']);
        }])->get();
    }

    /**
     * Get psychologist by ID
     */
    public function getPsychologistById(int $id): Psychologist
    {
        return Psychologist::findOrFail($id);
    }

    /**
     * Create a new psychologist
     */
    public function createPsychologist(array $data): Psychologist
    {
        return Psychologist::create($data);
    }

    /**
     * Update psychologist
     */
    public function updatePsychologist(int $id, array $data): Psychologist
    {
        $psychologist = Psychologist::findOrFail($id);
        $psychologist->update($data);
        return $psychologist;
    }

    /**
     * Delete psychologist
     */
    public function deletePsychologist(int $id): bool
    {
        $psychologist = Psychologist::findOrFail($id);
        return $psychologist->delete();
    }

    /**
     * Get available time slots for a psychologist on a specific date
     */
    public function getAvailableSlots(int $psychologistId, string $date): array
    {
        $psychologist = Psychologist::findOrFail($psychologistId);
        return $psychologist->getAvailableSlots($date);
    }

    /**
     * Check if psychologist is available at specific time
     */
    public function isPsychologistAvailable(int $psychologistId, string $startTime, string $endTime = null): bool
    {
        $psychologist = Psychologist::findOrFail($psychologistId);
        
        if (!$endTime) {
            $endTime = Carbon::parse($startTime)->addHour()->toDateTimeString();
        }

        // Check if psychologist has availability for this time
        if (!$psychologist->isAvailableAt($startTime, $endTime)) {
            return false;
        }

        // Check for existing bookings
        $existingSession = PsySession::where('psychologist_id', $psychologistId)
            ->where('start_time', $startTime)
            ->whereIn('status', ['booked', 'confirmed', 'in_progress'])
            ->first();

        return !$existingSession;
    }

    /**
     * Get psychologists with their next available slots
     */
    public function getPsychologistsWithAvailability(array $filters = []): Collection
    {
        $psychologists = $this->getAllPsychologists($filters);
        
        return $psychologists->map(function ($psychologist) use ($filters) {
            $date = $filters['date'] ?? Carbon::tomorrow()->toDateString();
            $availableSlots = $psychologist->getAvailableSlots($date);
            
            return [
                'id' => $psychologist->id,
                'name' => $psychologist->name,
                'specialty' => $psychologist->specialty,
                'bio' => $psychologist->bio,
                'hourly_rate' => $psychologist->hourly_rate,
                'available_slots' => $availableSlots,
                'next_available_date' => $this->getNextAvailableDate($psychologist),
            ];
        });
    }

    /**
     * Get next available date for a psychologist
     */
    private function getNextAvailableDate(Psychologist $psychologist): ?string
    {
        for ($i = 1; $i <= 30; $i++) { // Check next 30 days
            $date = Carbon::now()->addDays($i)->toDateString();
            $slots = $psychologist->getAvailableSlots($date);
            if (!empty($slots)) {
                return $date;
            }
        }
        return null;
    }

    /**
     * Get time slot range based on time filter
     */
    private function getTimeSlotRange(string $time): array
    {
        $ranges = [
            'morning' => ['start' => '08:00', 'end' => '12:00'],
            'afternoon' => ['start' => '12:00', 'end' => '17:00'],
            'evening' => ['start' => '17:00', 'end' => '20:00'],
        ];

        return $ranges[$time] ?? ['start' => '08:00', 'end' => '20:00'];
    }
}

