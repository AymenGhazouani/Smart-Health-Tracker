<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\SleepSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SleepDetailController extends Controller
{
    public function index()
    {
        $sleepSessions = SleepSession::where('user_id', Auth::id())
            ->orderByDesc('started_at')
            ->paginate(20);
        return view('metrics.sleep', compact('sleepSessions'));
    }

    public function create()
    {
        return view('metrics.sleep.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'quality' => 'nullable|integer|min:1|max:10',
            'note' => 'nullable|string|max:255',
        ]);

        $startedAt = Carbon::parse($request->started_at);
        $endedAt = Carbon::parse($request->ended_at);
        $durationMinutes = $endedAt->diffInMinutes($startedAt);

        SleepSession::create([
            'user_id' => Auth::id(),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMinutes,
            'quality' => $request->quality,
            'note' => $request->note,
        ]);

        return redirect()->route('metrics.sleep')->with('success', 'Sleep session added successfully!');
    }

    public function edit(SleepSession $sleepSession)
    {
        // Ensure user can only edit their own sleep sessions
        if ($sleepSession->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('metrics.sleep.edit', compact('sleepSession'));
    }

    public function update(Request $request, SleepSession $sleepSession)
    {
        // Ensure user can only update their own sleep sessions
        if ($sleepSession->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'quality' => 'nullable|integer|min:1|max:10',
            'note' => 'nullable|string|max:255',
        ]);

        $startedAt = Carbon::parse($request->started_at);
        $endedAt = Carbon::parse($request->ended_at);
        $durationMinutes = $endedAt->diffInMinutes($startedAt);

        $sleepSession->update([
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMinutes,
            'quality' => $request->quality,
            'note' => $request->note,
        ]);

        return redirect()->route('metrics.sleep')->with('success', 'Sleep session updated successfully!');
    }

    public function destroy(SleepSession $sleepSession)
    {
        // Ensure user can only delete their own sleep sessions
        if ($sleepSession->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $sleepSession->delete();

        return redirect()->route('metrics.sleep')->with('success', 'Sleep session deleted successfully!');
    }
}


