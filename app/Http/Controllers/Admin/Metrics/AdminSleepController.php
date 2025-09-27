<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\SleepSession;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSleepController extends Controller
{
    public function index(User $user)
    {
        $sleepSessions = SleepSession::where('user_id', $user->id)->orderByDesc('started_at')->paginate(20);
        return view('admin.metrics.sleep.index', compact('user','sleepSessions'));
    }

    public function create(User $user)
    {
        return view('admin.metrics.sleep.create', compact('user'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'started_at' => ['required','date'],
            'ended_at' => ['required','date','after:started_at'],
            'quality' => ['nullable','integer','between:1,10'],
            'note' => ['nullable','string','max:255'],
        ]);
        $duration = (int) floor((strtotime($data['ended_at']) - strtotime($data['started_at'])) / 60);
        SleepSession::create($data + [
            'user_id' => $user->id,
            'duration_minutes' => $duration,
        ]);
        return redirect()->route('admin.metrics.sleep.index', $user)->with('success', 'Sleep session added');
    }

    public function edit(User $user, SleepSession $sleepSession)
    {
        abort_unless($sleepSession->user_id === $user->id, 404);
        return view('admin.metrics.sleep.edit', compact('user','sleepSession'));
    }

    public function update(Request $request, User $user, SleepSession $sleepSession)
    {
        abort_unless($sleepSession->user_id === $user->id, 404);
        $data = $request->validate([
            'started_at' => ['required','date'],
            'ended_at' => ['required','date','after:started_at'],
            'quality' => ['nullable','integer','between:1,10'],
            'note' => ['nullable','string','max:255'],
        ]);
        $data['duration_minutes'] = (int) floor((strtotime($data['ended_at']) - strtotime($data['started_at'])) / 60);
        $sleepSession->update($data);
        return redirect()->route('admin.metrics.sleep.index', $user)->with('success', 'Sleep session updated');
    }

    public function destroy(User $user, SleepSession $sleepSession)
    {
        abort_unless($sleepSession->user_id === $user->id, 404);
        $sleepSession->delete();
        return redirect()->route('admin.metrics.sleep.index', $user)->with('success', 'Sleep session deleted');
    }
}


