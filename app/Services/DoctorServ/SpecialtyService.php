<?php

namespace App\Services\DoctorServ;

use App\Models\Specialty;

class SpecialtyService
{
    // Get all specialties
    public function getAll()
    {
        return Specialty::all();
    }

    // Get a specialty by ID
    public function getById($id)
    {
        return Specialty::findOrFail($id);
    }

    // Create a new specialty
    public function create(array $data)
    {
        return Specialty::create($data);
    }

    // Update an existing specialty
    public function update(Specialty $specialty, array $data)
    {
        $specialty->update($data);
        return $specialty;
    }

    // Delete a specialty
    public function delete(Specialty $specialty)
    {
        $specialty->delete();
        return true;
    }
}