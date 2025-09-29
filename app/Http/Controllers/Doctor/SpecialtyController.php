<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Services\DoctorServ\SpecialtyService;
class SpecialtyController extends Controller
{
    protected $specialtyService;

    public function __construct(SpecialtyService $specialtyService)
    {
        $this->specialtyService = $specialtyService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    $specialties = $this->specialtyService->getAll();
    return view('specialties.index', compact('specialties'));
}

public function create()
{
    return view('specialties.create');
}

public function store(Request $request)
{
    if (!$request->user()->isAdmin()) {
        return redirect()->back()->with('error', 'Forbidden');
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $this->specialtyService->create($data);

return redirect()->back()->with('success', 'Specialty created successfully!');}

public function edit(Specialty $specialty)
{
    return view('specialties.edit', compact('specialty'));
}

public function update(Request $request, Specialty $specialty)
{
    if (!$request->user()->isAdmin()) {
        return redirect()->back()->with('error', 'Forbidden');
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $this->specialtyService->update($specialty, $data);

    return redirect()->route('admin.doctor.index')->with('success', 'Specialty updated successfully!');
}

public function destroy(Request $request, Specialty $specialty)
{
    if (!$request->user()->isAdmin()) {
        return redirect()->back()->with('error', 'Forbidden');
    }

    $this->specialtyService->delete($specialty);

    // Stay on the same page
    return redirect()->back()->with('success', 'Specialty deleted successfully!');
}



}