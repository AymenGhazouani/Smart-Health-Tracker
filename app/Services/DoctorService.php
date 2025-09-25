<?php

namespace App\Services;

use App\Models\Doctor;

class DoctorService
{
    public function getAll()
    {
        return Doctor::all();
    }

    public function getById($id)
    {
        return Doctor::findOrFail($id);
    }

    public function create(array $data)
    {
        // make sure only fillable fields are passed
        return Doctor::create($data);
    }

    public function update(Doctor $doctor, array $data)
    {
        $doctor->update($data);
        return $doctor;
    }

    public function delete(Doctor $doctor)
    {
        $doctor->delete();
        return true;
    }
}
