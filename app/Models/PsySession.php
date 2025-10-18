<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PsySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'psychologist_id',
        'patient_id',
        'start_time',
        'end_time',
        'status',
        'notes',
        'session_fee',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'session_fee' => 'decimal:2',
    ];

    /**
     * Validation rules for the model
     */
    public static function validationRules(): array
    {
        return [
            'psychologist_id' => 'required|exists:psychologists,id',
            'patient_id' => 'required|exists:users,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'status' => 'sometimes|in:booked,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
            'session_fee' => 'nullable|numeric|min:0|max:999999.99',
        ];
    }

    /**
     * Get the psychologist for this session
     */
    public function psychologist(): BelongsTo
    {
        return $this->belongsTo(Psychologist::class);
    }

    /**
     * Get the patient for this session
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get all notes for this session
     */
    public function sessionNotes(): HasMany
    {
        return $this->hasMany(PsyNote::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by psychologist
     */
    public function scopeByPsychologist($query, $psychologistId)
    {
        return $query->where('psychologist_id', $psychologistId);
    }

    /**
     * Scope to filter by patient
     */
    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }

    /**
     * Scope to get upcoming sessions
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())
            ->whereIn('status', ['booked', 'confirmed']);
    }

    /**
     * Scope to get past sessions
     */
    public function scopePast($query)
    {
        return $query->where('start_time', '<', now());
    }

    /**
     * Check if session can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['booked', 'confirmed']) && 
               $this->start_time > now()->addHours(24); // Can cancel up to 24 hours before
    }

    /**
     * Check if session can be rescheduled
     */
    public function canBeRescheduled()
    {
        return in_array($this->status, ['booked', 'confirmed']) && 
               $this->start_time > now()->addHours(48); // Can reschedule up to 48 hours before
    }

    /**
     * Get session duration in minutes
     */
    public function getDurationAttribute()
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Get formatted session time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->start_time->format('M j, Y g:i A') . ' - ' . $this->end_time->format('g:i A');
    }
}

