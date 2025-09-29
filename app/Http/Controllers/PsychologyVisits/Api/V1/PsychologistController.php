<?php

namespace App\Http\Controllers\PsychologyVisits\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PsychologyVisits\StorePsychologistRequest;
use App\Http\Requests\PsychologyVisits\UpdatePsychologistRequest;
use App\Services\PsychologyVisits\PsychologistService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PsychologistController extends Controller
{
    private PsychologistService $psychologistService;

    public function __construct(PsychologistService $psychologistService)
    {
        $this->psychologistService = $psychologistService;
    }

    /**
     * Display a listing of psychologists with filters
     * 
     * Query parameters:
     * - specialty: Filter by specialty (e.g., anxiety, depression)
     * - date: Show psychologists available on specific date (YYYY-MM-DD)
     * - time: Filter by time slot (morning, afternoon, evening)
     * - with_availability: Include available slots in response
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['specialty', 'date', 'time']);
            
            // If with_availability is requested, get detailed availability info
            if ($request->boolean('with_availability')) {
                $psychologists = $this->psychologistService->getPsychologistsWithAvailability($filters);
            } else {
                $psychologists = $this->psychologistService->getAllPsychologists($filters);
            }

            return response()->json([
                'success' => true,
                'data' => $psychologists,
                'message' => 'Psychologists retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve psychologists',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created psychologist
     */
    public function store(StorePsychologistRequest $request): JsonResponse
    {
        try {
            $psychologist = $this->psychologistService->createPsychologist($request->validated());

            return response()->json([
                'success' => true,
                'data' => $psychologist,
                'message' => 'Psychologist created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create psychologist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified psychologist
     */
    public function show(int $id): JsonResponse
    {
        try {
            $psychologist = $this->psychologistService->getPsychologistById($id);

            return response()->json([
                'success' => true,
                'data' => $psychologist,
                'message' => 'Psychologist retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Psychologist not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified psychologist
     */
    public function update(UpdatePsychologistRequest $request, int $id): JsonResponse
    {
        try {
            $psychologist = $this->psychologistService->updatePsychologist($id, $request->validated());

            return response()->json([
                'success' => true,
                'data' => $psychologist,
                'message' => 'Psychologist updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update psychologist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified psychologist
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->psychologistService->deletePsychologist($id);

            return response()->json([
                'success' => true,
                'message' => 'Psychologist deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete psychologist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available time slots for a psychologist on a specific date
     * 
     * GET /api/v1/psychologists/{id}/availability?date=YYYY-MM-DD
     */
    public function availability(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'date' => 'required|date|after_or_equal:today'
            ]);

            $date = $request->input('date');
            $slots = $this->psychologistService->getAvailableSlots($id, $date);

            return response()->json([
                'success' => true,
                'data' => [
                    'psychologist_id' => $id,
                    'date' => $date,
                    'available_slots' => $slots
                ],
                'message' => 'Available slots retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve available slots',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if psychologist is available at specific time
     * 
     * POST /api/v1/psychologists/{id}/check-availability
     */
    public function checkAvailability(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'start_time' => 'required|date|after:now',
                'end_time' => 'nullable|date|after:start_time'
            ]);

            $startTime = $request->input('start_time');
            $endTime = $request->input('end_time');
            
            $isAvailable = $this->psychologistService->isPsychologistAvailable($id, $startTime, $endTime);

            return response()->json([
                'success' => true,
                'data' => [
                    'psychologist_id' => $id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_available' => $isAvailable
                ],
                'message' => $isAvailable ? 'Time slot is available' : 'Time slot is not available'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check availability',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

