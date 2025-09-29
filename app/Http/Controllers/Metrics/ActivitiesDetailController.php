<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivitiesDetailController extends Controller
{
    public function index()
    {
        $activities = Activity::where('user_id', Auth::id())
            ->orderByDesc('performed_at')
            ->paginate(20);
        return view('metrics.activities', compact('activities'));
    }

    public function create()
    {
        return view('metrics.activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'performed_at' => 'required|date',
            'type' => 'required|string|in:running,cycling,swimming,walking,gym,yoga,dancing,hiking,sports,other',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'distance_km_times100' => 'nullable|numeric|min:0',
            'calories' => 'nullable|integer|min:0|max:5000',
            'note' => 'nullable|string|max:255',
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'performed_at' => $request->performed_at,
            'type' => $request->type,
            'duration_minutes' => $request->duration_minutes,
            'distance_km_times100' => $request->distance_km_times100,
            'calories' => $request->calories,
            'note' => $request->note,
        ]);

        return redirect()->route('metrics.activities')->with('success', 'Activity added successfully!');
    }

    public function edit(Activity $activity)
    {
        // Ensure user can only edit their own activities
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('metrics.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        // Ensure user can only update their own activities
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'performed_at' => 'required|date',
            'type' => 'required|string|in:running,cycling,swimming,walking,gym,yoga,dancing,hiking,sports,other',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'distance_km_times100' => 'nullable|numeric|min:0',
            'calories' => 'nullable|integer|min:0|max:5000',
            'note' => 'nullable|string|max:255',
        ]);

        $activity->update([
            'performed_at' => $request->performed_at,
            'type' => $request->type,
            'duration_minutes' => $request->duration_minutes,
            'distance_km_times100' => $request->distance_km_times100,
            'calories' => $request->calories,
            'note' => $request->note,
        ]);

        return redirect()->route('metrics.activities')->with('success', 'Activity updated successfully!');
    }

    public function destroy(Activity $activity)
    {
        // Ensure user can only delete their own activities
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $activity->delete();

        return redirect()->route('metrics.activities')->with('success', 'Activity deleted successfully!');
    }
}


