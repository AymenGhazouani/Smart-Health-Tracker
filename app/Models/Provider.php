<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialty', 'bio', 'profile_image', 'hourly_rate', 'is_active'
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
}
