<?php

namespace App\Http\Controllers;

use App\Services\HealthPredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthPredictionController extends Controller
{
    protected $predictionService;

    public function __construct(HealthPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Show prediction dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $weightPrediction = $this->predictionService->predictWeightTrend($user);
        $sleepPrediction = $this->predictionService->predictSleepImprovement($user);

        return view('health-predictions.dashboard', compact('weightPrediction', 'sleepPrediction'));
    }

    /**
     * Get weight goal recommendations
     */
    public function weightGoal(Request $request)
    {
        $request->validate([
            'target_weight' => 'required|numeric|min:30|max:300',
            'timeframe_days' => 'required|integer|min:7|max:365'
        ]);

        $user = Auth::user();
        $prediction = $this->predictionService->predictActivityForWeightGoal(
            $user,
            $request->target_weight,
            $request->timeframe_days
        );

        return response()->json($prediction);
    }

    /**
     * Get weight trend prediction
     */
    public function weightTrend(Request $request)
    {
        $days = $request->get('days', 30);
        $user = Auth::user();
        
        $prediction = $this->predictionService->predictWeightTrend($user, $days);
        
        return response()->json($prediction);
    }
}