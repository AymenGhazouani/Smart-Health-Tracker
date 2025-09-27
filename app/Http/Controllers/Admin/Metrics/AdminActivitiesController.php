<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class AdminActivitiesController extends Controller
{
    public function index(User $user)
    {
        $activities = Activity::where('user_id', $user->id)->orderByDesc('performed_at')->paginate(20);
        return view('admin.metrics.activities.index', compact('user','activities'));
    }

    public function create(User $user)
    {
        return view('admin.metrics.activities.create', compact('user'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'type' => ['required','string','max:100'],
            'duration_minutes' => ['required','integer','min:1','max:1440'],
            'calories' => ['nullable','integer','min:0','max:20000'],
            'distance_km' => ['nullable','numeric','min:0','max:1000'],
            'performed_at' => ['required','date'],
            'note' => ['nullable','string','max:255'],
        ]);
        Activity::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'duration_minutes' => $data['duration_minutes'],
            'calories' => $data['calories'] ?? null,
            'distance_km_times100' => isset($data['distance_km']) ? (int) round($data['distance_km'] * 100) : null,
            'performed_at' => $data['performed_at'],
            'note' => $data['note'] ?? null,
        ]);
        return redirect()->route('admin.metrics.activities.index', $user)->with('success', 'Activity added');
    }

    public function edit(User $user, Activity $activity)
    {
        abort_unless($activity->user_id === $user->id, 404);
        return view('admin.metrics.activities.edit', compact('user','activity'));
    }

    public function update(Request $request, User $user, Activity $activity)
    {
        abort_unless($activity->user_id === $user->id, 404);
        $data = $request->validate([
            'type' => ['required','string','max:100'],
            'duration_minutes' => ['required','integer','min:1','max:1440'],
            'calories' => ['nullable','integer','min:0','max:20000'],
            'distance_km' => ['nullable','numeric','min:0','max:1000'],
            'performed_at' => ['required','date'],
            'note' => ['nullable','string','max:255'],
        ]);
        if (array_key_exists('distance_km', $data)) {
            $data['distance_km_times100'] = isset($data['distance_km']) ? (int) round($data['distance_km'] * 100) : null;
            unset($data['distance_km']);
        }
        $activity->update($data);
        return redirect()->route('admin.metrics.activities.index', $user)->with('success', 'Activity updated');
    }

    public function destroy(User $user, Activity $activity)
    {
        abort_unless($activity->user_id === $user->id, 404);
        $activity->delete();
        return redirect()->route('admin.metrics.activities.index', $user)->with('success', 'Activity deleted');
    }
}


