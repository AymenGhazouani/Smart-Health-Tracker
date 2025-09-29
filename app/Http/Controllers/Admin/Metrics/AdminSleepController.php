<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\SleepSession;
use App\Models\User;
use App\Notifications\MetricsReminderNotification;
use Illuminate\Http\Request;

class AdminSleepController extends Controller
{
    public function index(User $user)
    {
        $sleepSessions = SleepSession::where('user_id', $user->id)->orderByDesc('started_at')->paginate(20);
        return view('admin.metrics.sleep.index', compact('user','sleepSessions'));
    }

    public function notifyUser(Request $request, User $user)
    {
        $request->validate([
            'message' => ['nullable','string','max:255'],
        ]);

        $message = $request->input('message') ?: 'Please log a recent sleep session to keep your metrics up to date.';
        $user->notify(new MetricsReminderNotification('sleep', $message));

        return back()->with('success', 'User has been notified about sleep updates.');
    }
}


