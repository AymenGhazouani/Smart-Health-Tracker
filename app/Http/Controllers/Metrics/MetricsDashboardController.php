<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\SleepSession;
use App\Models\Weight;
use Illuminate\Support\Facades\Auth;

class MetricsDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $latestWeight = Weight::where('user_id', $userId)
            ->orderByDesc('measured_at')
            ->first();

        $avgSleepMins = SleepSession::where('user_id', $userId)
            ->where('started_at', '>=', now()->subDays(7))
            ->avg('duration_minutes');

        $activityTotals = Activity::where('user_id', $userId)
            ->where('performed_at', '>=', now()->subDays(7))
            ->selectRaw('sum(duration_minutes) as minutes, sum(calories) as calories')
            ->first();

        return view('metrics.dashboard', [
            'latestWeight' => $latestWeight,
            'avgSleepMins' => $avgSleepMins ? (int) round($avgSleepMins) : null,
            'activityTotals' => $activityTotals,
        ]);
    }
}


