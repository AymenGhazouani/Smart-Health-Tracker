<?php

namespace App\Services\DoctorServ;

use App\Models\Doctor;
use App\Models\DoctorReview;

class DoctorReviewService
{
    // Get all reviews for a doctor
    public function getByDoctor(Doctor $doctor)
    {
        return $doctor->reviews; // make sure Doctor model has reviews() relation
    }

    // Create a review for a doctor
    public function create(Doctor $doctor, array $data)
    {
        $data['doctor_id'] = $doctor->id;
        return DoctorReview::create($data);
    }

    // Optionally, get a single review by ID
    public function getById($id)
    {
        return DoctorReview::findOrFail($id);
    }

    // Optionally, update a review
    public function update(DoctorReview $review, array $data)
    {
        $review->update($data);
        return $review;
    }

    // Optionally, delete a review
    public function delete(DoctorReview $review)
    {
        $review->delete();
        return true;
    }
}