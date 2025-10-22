<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialty', 'bio', 'profile_image', 'hourly_rate', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hourly_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilitySlots()
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes for advanced filtering
    public function scopeHourlyRateRange(Builder $query, $range)
    {
        if (is_string($range)) {
            $range = explode(',', $range);
        }
        
        if (count($range) === 2) {
            return $query->whereBetween('hourly_rate', [$range[0], $range[1]]);
        }
        
        return $query;
    }

    public function scopeCreatedDateRange(Builder $query, $range)
    {
        if (is_string($range)) {
            $range = explode(',', $range);
        }
        
        if (count($range) === 2) {
            return $query->whereBetween('created_at', [$range[0], $range[1]]);
        }
        
        return $query;
    }

    public function scopeHasAppointments(Builder $query)
    {
        return $query->has('appointments');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySpecialty(Builder $query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user->name ?? 'N/A';
    }

    public function getAppointmentsCountAttribute()
    {
        return $this->appointments()->count();
    }

    public function getFormattedHourlyRateAttribute()
    {
        return $this->hourly_rate ? '$' . number_format($this->hourly_rate, 2) : 'Not set';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? '<span class="badge bg-success">Active</span>' 
            : '<span class="badge bg-danger">Inactive</span>';
    }
}
