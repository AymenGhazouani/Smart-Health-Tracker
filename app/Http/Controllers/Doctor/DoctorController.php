<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
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
    public function index()
    {
        return $this->doctorService->getAll();
    }

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
        ]);

        return $this->doctorService->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        return $this->doctorService->getById($doctor->id);
    }

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
            // 'specialty_id' => 'sometimes|exists:specialties,id',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        return $this->doctorService->update($doctor, $data);
    }

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
    return response()->noContent();
}

}