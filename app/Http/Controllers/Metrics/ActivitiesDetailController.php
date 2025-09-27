<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivitiesDetailController extends Controller
{
    public function index()
    {
        $activities = Activity::where('user_id', Auth::id())
            ->orderByDesc('performed_at')
            ->paginate(20);
        return view('metrics.activities', compact('activities'));
    }
}


