<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Psychologist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialty',
        'bio',
        'availability',
        'hourly_rate',
        'is_active',
    ];

    protected $casts = [
        'availability' => 'array',
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get all sessions for this psychologist
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(PsySession::class);
    }

    /**
     * Get all notes written by this psychologist
     */
    public function notes(): HasMany
    {
        return $this->hasMany(PsyNote::class);
    }

    /**
     * Scope to get only active psychologists
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by specialty
     */
    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', 'like', '%' . $specialty . '%');
    }

    /**
     * Check if psychologist is available at a specific time
     */
    public function isAvailableAt($startTime, $endTime = null)
    {
        if (!$this->availability) {
            return false;
        }

        $requestedDay = strtolower(date('l', strtotime($startTime)));
        $requestedTime = date('H:i', strtotime($startTime));

        // Check if the day is in availability
        if (!isset($this->availability[$requestedDay])) {
            return false;
        }

        // Check if the time falls within available slots
        foreach ($this->availability[$requestedDay] as $slot) {
            if ($requestedTime >= $slot['start'] && $requestedTime <= $slot['end']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots($date)
    {
        $day = strtolower(date('l', strtotime($date)));
        
        if (!isset($this->availability[$day])) {
            return [];
        }

        // Get existing bookings for this date
        $existingBookings = $this->sessions()
            ->whereDate('start_time', $date)
            ->whereIn('status', ['booked', 'confirmed', 'in_progress'])
            ->pluck('start_time')
            ->map(function ($time) {
                return date('H:i', strtotime($time));
            })
            ->toArray();

        $availableSlots = [];
        foreach ($this->availability[$day] as $slot) {
            $start = $slot['start'];
            $end = $slot['end'];
            
            // Generate hourly slots within this time range
            $current = $start;
            while ($current < $end) {
                if (!in_array($current, $existingBookings)) {
                    $availableSlots[] = [
                        'time' => $current,
                        'formatted_time' => date('g:i A', strtotime($current . ':00')),
                    ];
                }
                $current = date('H:i', strtotime($current . ':00 +1 hour'));
            }
        }

        return $availableSlots;
    }
}

