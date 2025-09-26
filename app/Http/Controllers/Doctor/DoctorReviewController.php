<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorReview;
use App\Services\DoctorServ\DoctorReviewService;
class DoctorReviewController extends Controller
{
    protected $reviewService;

    public function __construct(DoctorReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Doctor $doctor)
    {
        return $this->reviewService->getByDoctor($doctor);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Doctor $doctor)
{
    $data = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    // Assign the authenticated user's ID
    $data['user_id'] = $request->user()->id;

    return $this->reviewService->create($doctor, $data);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorReview $review)
    {
        return $this->reviewService->getById($review->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DoctorReview $review)
    {
        if ($request->user()->id !== $review->user_id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

        $data = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        return $this->reviewService->update($review, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, DoctorReview $review)
{
    if ($request->user()->id !== $review->user_id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $this->reviewService->delete($review);
    return response()->noContent();
}

}