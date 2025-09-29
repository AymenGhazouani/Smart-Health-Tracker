<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'symptoms', 'diagnosis', 'treatment_plan',
        'notes', 'prescriptions', 'follow_up_required'
    ];

    protected $casts = [
        'follow_up_required' => 'boolean',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
