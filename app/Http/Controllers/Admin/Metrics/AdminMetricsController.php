<?php

namespace App\Http\Controllers\Admin\Metrics;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Weight;
use App\Models\SleepSession;
use App\Models\Activity;
use Illuminate\Http\Request;

class AdminMetricsController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get(['id','name','email']);
        $selectedUserId = (int) $request->get('user_id', 0);

        $latestWeight = null;
        $avgSleepMins = null;
        $activityTotals = null;

        if ($selectedUserId) {
            $latestWeight = Weight::where('user_id', $selectedUserId)
                ->orderByDesc('measured_at')
                ->first();

            $avgSleepMins = SleepSession::where('user_id', $selectedUserId)
                ->where('started_at', '>=', now()->subDays(7))
                ->avg('duration_minutes');

            $activityTotals = Activity::where('user_id', $selectedUserId)
                ->where('performed_at', '>=', now()->subDays(7))
                ->selectRaw('sum(duration_minutes) as minutes, sum(calories) as calories')
                ->first();
        }

        return view('admin.metrics.dashboard', [
            'users' => $users,
            'selectedUserId' => $selectedUserId,
            'latestWeight' => $latestWeight,
            'avgSleepMins' => $avgSleepMins ? (int) round($avgSleepMins) : null,
            'activityTotals' => $activityTotals,
        ]);
    }
}


