<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SleepSession;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SleepSessionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = SleepSession::where('user_id', $userId)->orderByDesc('started_at');

        if ($request->filled('from')) {
            $query->where('started_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->where('ended_at', '<=', $request->date('to'));
        }

        return $query->paginate(50);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'started_at' => ['required', 'date'],
            'ended_at' => ['required', 'date', 'after:started_at'],
            'quality' => ['nullable', 'integer', 'between:1,10'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $duration = (int) floor((strtotime($data['ended_at']) - strtotime($data['started_at'])) / 60);

        $sleep = SleepSession::create([
            'user_id' => Auth::id(),
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'duration_minutes' => $duration,
            'quality' => $data['quality'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        return response()->json($sleep, Response::HTTP_CREATED);
    }

    public function show(SleepSession $sleepSession)
    {
        $this->authorizeOwnership($sleepSession);
        return $sleepSession;
    }

    public function update(Request $request, SleepSession $sleepSession)
    {
        $this->authorizeOwnership($sleepSession);

        $data = $request->validate([
            'started_at' => ['sometimes', 'date'],
            'ended_at' => ['sometimes', 'date'],
            'quality' => ['nullable', 'integer', 'between:1,10'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if (isset($data['started_at']) || isset($data['ended_at'])) {
            $start = $data['started_at'] ?? $sleepSession->started_at;
            $end = $data['ended_at'] ?? $sleepSession->ended_at;
            $data['duration_minutes'] = (int) floor((strtotime($end) - strtotime($start)) / 60);
        }

        $sleepSession->update($data);
        return $sleepSession;
    }

    public function destroy(SleepSession $sleepSession)
    {
        $this->authorizeOwnership($sleepSession);
        $sleepSession->delete();
        return response()->noContent();
    }

    private function authorizeOwnership(SleepSession $sleepSession): void
    {
        abort_unless($sleepSession->user_id === Auth::id(), Response::HTTP_FORBIDDEN);
    }
}


