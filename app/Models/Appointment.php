<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'provider_id', 'availability_slot_id', 'scheduled_time',
        'status', 'meeting_link', 'reason'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function availabilitySlot()
    {
        return $this->belongsTo(AvailabilitySlot::class);
    }

    public function visitSummary()
    {
        return $this->hasOne(VisitSummary::class);
    }
}
