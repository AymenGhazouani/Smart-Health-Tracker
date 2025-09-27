<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\DoctorReview;
use App\Services\DoctorServ\DoctorReviewService;
use App\Services\DoctorServ\DoctorService;
class DoctorController extends Controller
{
    protected $doctorService;
    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

public function index(Request $request)
{
    $specialtyId = $request->query('specialty');
    $specialties = Specialty::all();

    $doctors = Doctor::when($specialtyId, function ($query, $specialtyId) {
        return $query->where('specialty_id', $specialtyId);
    })->paginate(10);

    if (auth()->check() && auth()->user()->isAdmin()) {
        return view('admin.doctor.index', compact('doctors', 'specialties', 'specialtyId'));
    }

    return view('doctors.index', compact('doctors', 'specialties', 'specialtyId'));
}



public function edit(Doctor $doctor)
{
    if (auth()->check() && auth()->user()->isAdmin()) {
        $specialties = Specialty::all(); // fetch all specialties
        return view('admin.doctor.edit', compact('doctor', 'specialties'));
    }
}


public function create()
{
    if (auth()->check() && auth()->user()->isAdmin()) {
        $specialties = Specialty::all(); // fetch all specialties
        return view('admin.doctor.create', compact('specialties'));
    }

    // Front create if needed
}




    // public function index()
    // {
    //     // Get all doctors (or paginate if you want)
    //     $doctors = $this->doctorService->getAll(); // Should return a collection or paginator

    //     // Return Blade view
    //     return view('doctors.index', compact('doctors'));
    // }


    // public function index()
    // {
    //     return $this->doctorService->getAll();
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
{
    if (!$request->user()->isAdmin()) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:doctors,email',
        'specialty_id' => 'required|exists:specialties,id',
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string',
    ]);

    $doctor = Doctor::create($data);

    return redirect()->route('doctor.index')->with('success', 'Doctor created successfully!');
}

//     public function store(Request $request, DoctorReviewService $doctorReviewService)
// {
//     // Validate input
//     $data = $request->validate([
//         'doctor_id' => 'required|exists:doctors,id',
//         'rating' => 'required|integer|min:1|max:5',
//         'comment' => 'required|string',
//     ]);

//     // Fetch the doctor safely
//     $doctor = Doctor::findOrFail($data['doctor_id']); // ensures doctor exists

//     // Prepare review data
//     $reviewData = [
//         'rating' => $data['rating'],
//         'comment' => $data['comment'],
//         'user_id' => $request->user()->id, // make sure the user is logged in
//     ];

//     // Create the review
//     $doctorReviewService->create($doctor, $reviewData);

//     return redirect()->back()->with('success', 'Review added successfully!');
// }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show(Doctor $doctor)
{
    // Optional: keep the service if it fetches extra data
    $doctorData = $this->doctorService->getById($doctor->id);

    // Pass the doctor model to a Blade view
    return view('doctors.show', ['doctor' => $doctorData]);
}


    // public function show(Doctor $doctor)
    // {
    //     return $this->doctorService->getById($doctor->id);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Doctor $doctor)
{
    if (!$request->user()->isAdmin()) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $data = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:doctors,email,' . $doctor->id,
         'specialty_id' => 'sometimes|exists:specialties,id',
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string',
    ]);

    $this->doctorService->update($doctor, $data);
return redirect()->route('doctor.index')->with('success', 'Doctor updated successfully!');

}




    //  public function update(Request $request, Doctor $doctor)
    // {
    //     if (!$request->user()->isAdmin()) {
    //         return response()->json(['message' => 'Forbidden'], 403);
    //     }

    //     $data = $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|email|unique:doctors,email,' . $doctor->id,
    //         // 'specialty_id' => 'sometimes|exists:specialties,id',
    //         'phone' => 'nullable|string|max:20',
    //         'bio' => 'nullable|string',
    //     ]);

    //     return $this->doctorService->update($doctor, $data);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, Doctor $doctor)
{
    if (!$request->user()->isAdmin()) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $this->doctorService->delete($doctor);
return redirect()->route('doctor.index')->with('success', 'Doctor deleted successfully!');

}



//     public function destroy(Request $request, Doctor $doctor)
// {
//     if (!$request->user()->isAdmin()) {
//         return response()->json(['message' => 'Forbidden'], 403);
//     }

//     $this->doctorService->delete($doctor);
//     return response()->noContent();
// }

}