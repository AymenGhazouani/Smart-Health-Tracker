<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Specialty;

class StatsController extends Controller
{
    public function doctorsPerSpecialty()
    {
        $data = Specialty::withCount('doctors')->get();

        // Extract labels and values for the charts
        $labels = $data->pluck('name');
        $values = $data->pluck('doctors_count');

        return view('admin.stats.doctors_per_specialty', compact('labels', 'values'));
    }
}
