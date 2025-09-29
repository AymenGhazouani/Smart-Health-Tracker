<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Weight;
use App\Notifications\MetricsReminderNotification;
use Illuminate\Http\Request;

class AdminWeightsController extends Controller
{
    public function index(User $user)
    {
        $weights = Weight::where('user_id', $user->id)->orderByDesc('measured_at')->paginate(20);
        return view('admin.metrics.weights.index', compact('user', 'weights'));
    }

    public function notifyUser(Request $request, User $user)
    {
        $request->validate([
            'message' => ['nullable','string','max:255'],
        ]);

        $message = $request->input('message') ?: 'Please log a recent weight entry to keep your metrics up to date.';
        $user->notify(new MetricsReminderNotification('weight', $message));

        return back()->with('success', 'User has been notified about weight updates.');
    }
}


