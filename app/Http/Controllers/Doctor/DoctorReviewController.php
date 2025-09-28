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
    public function store(Request $request)
{
    $data = $request->validate([
        'doctor_id' => 'required|exists:doctors,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
    ]);

    // Fetch the doctor model
    $doctor = Doctor::findOrFail($data['doctor_id']);

    // Assign the authenticated user's ID
    $data['user_id'] = $request->user()->id;

    // Create the review
    $this->reviewService->create($doctor, $data);

    return redirect()->back()->with('success', 'Review added successfully!');
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
    public function edit(DoctorReview $review)
{
    if (auth()->id() !== $review->user_id) {
        abort(403);
    }

    return view('reviews.edit', compact('review'));
}


    public function update(Request $request, DoctorReview $review)
{
    if ($request->user()->id !== $review->user_id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string',
    ]);

    $this->reviewService->update($review, $data);

    // Redirect back to the doctor show page
    return redirect()->route('doctors.show', $review->doctor_id)
                     ->with('success', 'Review updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy(Request $request, DoctorReview $review)
{
    if (!auth()->user()->isAdmin() && auth()->id() !== $review->user_id) {
        return redirect()->back()->with('error', 'Unauthorized');
    }

    $review->delete();
    return redirect()->back()->with('success', 'Review deleted successfully!');
}


}