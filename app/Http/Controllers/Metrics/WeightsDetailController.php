<?php

namespace App\Http\Controllers\Metrics;

use App\Http\Controllers\Controller;
use App\Models\Weight;
use Illuminate\Support\Facades\Auth;

class WeightsDetailController extends Controller
{
    public function index()
    {
        $weights = Weight::where('user_id', Auth::id())
            ->orderByDesc('measured_at')
            ->paginate(20);
        return view('metrics.weights', compact('weights'));
    }
}


