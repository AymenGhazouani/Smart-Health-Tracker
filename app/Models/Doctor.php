<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'email',
        'specialty_id',
        'phone',
        'description'
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function reviews()
    {
        return $this->hasMany(DoctorReview::class);
    }
}