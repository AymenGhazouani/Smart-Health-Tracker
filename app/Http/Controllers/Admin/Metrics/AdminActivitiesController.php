<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\MetricsReminderNotification;
use Illuminate\Http\Request;

class AdminActivitiesController extends Controller
{
    public function index(User $user)
    {
        $activities = Activity::where('user_id', $user->id)->orderByDesc('performed_at')->paginate(20);
        return view('admin.metrics.activities.index', compact('user','activities'));
    }

    public function notifyUser(Request $request, User $user)
    {
        $request->validate([
            'message' => ['nullable','string','max:255'],
        ]);

        $message = $request->input('message') ?: 'Please log a recent activity to keep your metrics up to date.';
        $user->notify(new MetricsReminderNotification('activity', $message));

        return back()->with('success', 'User has been notified about activity updates.');
    }
}


