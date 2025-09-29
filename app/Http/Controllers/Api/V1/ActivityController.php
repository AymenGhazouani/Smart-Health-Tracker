<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Activity::where('user_id', $userId)->orderByDesc('performed_at');

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }
        if ($request->filled('from')) {
            $query->where('performed_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->where('performed_at', '<=', $request->date('to'));
        }

        return $query->paginate(50);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'calories' => ['nullable', 'integer', 'min:0', 'max:20000'],
            'distance_km' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'performed_at' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $activity = Activity::create([
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'duration_minutes' => $data['duration_minutes'],
            'calories' => $data['calories'] ?? null,
            'distance_km_times100' => isset($data['distance_km']) ? (int) round($data['distance_km'] * 100) : null,
            'performed_at' => $data['performed_at'],
            'note' => $data['note'] ?? null,
        ]);

        return response()->json($activity, Response::HTTP_CREATED);
    }

    public function show(Activity $activity)
    {
        $this->authorizeOwnership($activity);
        return $activity;
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorizeOwnership($activity);

        $data = $request->validate([
            'type' => ['sometimes', 'string', 'max:100'],
            'duration_minutes' => ['sometimes', 'integer', 'min:1', 'max:1440'],
            'calories' => ['nullable', 'integer', 'min:0', 'max:20000'],
            'distance_km' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'performed_at' => ['sometimes', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_key_exists('distance_km', $data)) {
            $data['distance_km_times100'] = isset($data['distance_km']) ? (int) round($data['distance_km'] * 100) : null;
            unset($data['distance_km']);
        }

        $activity->update($data);
        return $activity;
    }

    public function destroy(Activity $activity)
    {
        $this->authorizeOwnership($activity);
        $activity->delete();
        return response()->noContent();
    }

    private function authorizeOwnership(Activity $activity): void
    {
        abort_unless($activity->user_id === Auth::id(), Response::HTTP_FORBIDDEN);
    }
}


