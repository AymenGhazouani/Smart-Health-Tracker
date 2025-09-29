<?php

namespace App\Http\Controllers\PsychologyVisits;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StorePsychologistRequest;
use App\Http\Requests\PsychologyVisits\UpdatePsychologistRequest;
use App\Services\PsychologyVisits\PsychologistService;
use Illuminate\Http\Request;

class AdminPsychologistController extends Controller
{
    private PsychologistService $psychologistService;

    public function __construct(PsychologistService $psychologistService)
    {
        $this->psychologistService = $psychologistService;
    }

    /**
     * Display a listing of psychologists for admin
     */
    public function index()
    {
        $psychologists = $this->psychologistService->getAllPsychologists();
        
        // Load sessions for each psychologist
        $psychologists->load('sessions');
        
        return view('admin.psychologists.index', compact('psychologists'));
    }

    /**
     * Show the form for creating a new psychologist
     */
    public function create()
    {
        return view('admin.psychologists.create');
    }

    /**
     * Store a newly created psychologist
     */
    public function store(StorePsychologistRequest $request)
    {
        try {
            $psychologist = $this->psychologistService->createPsychologist($request->validated());
            
            return redirect()->route('admin.psychologists.index')
                ->with('success', 'Psychologist created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create psychologist: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified psychologist
     */
    public function show(int $id)
    {
        try {
            $psychologist = $this->psychologistService->getPsychologistById($id);
            $psychologist->load(['sessions.patient']);
            
            return view('admin.psychologists.show', compact('psychologist'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psychologists.index')
                ->with('error', 'Psychologist not found.');
        }
    }

    /**
     * Show the form for editing the specified psychologist
     */
    public function edit(int $id)
    {
        try {
            $psychologist = $this->psychologistService->getPsychologistById($id);
            return view('admin.psychologists.edit', compact('psychologist'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.psychologists.index')
                ->with('error', 'Psychologist not found.');
        }
    }

    /**
     * Update the specified psychologist
     */
    public function update(UpdatePsychologistRequest $request, int $id)
    {
        try {
            $psychologist = $this->psychologistService->updatePsychologist($id, $request->validated());
            
            return redirect()->route('admin.psychologists.show', $id)
                ->with('success', 'Psychologist updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update psychologist: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified psychologist
     */
    public function destroy(int $id)
    {
        try {
            $this->psychologistService->deletePsychologist($id);
            
            return redirect()->route('admin.psychologists.index')
                ->with('success', 'Psychologist deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.psychologists.index')
                ->with('error', 'Failed to delete psychologist: ' . $e->getMessage());
        }
    }
}

