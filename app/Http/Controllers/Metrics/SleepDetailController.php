<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\SleepSession;
use Illuminate\Support\Facades\Auth;

class SleepDetailController extends Controller
{
    public function index()
    {
        $sleepSessions = SleepSession::where('user_id', Auth::id())
            ->orderByDesc('started_at')
            ->paginate(20);
        return view('metrics.sleep', compact('sleepSessions'));
    }
}


