<?php

namespace App\Http\Controllers;

use App\Services\HealthMLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthMLController extends Controller
{
    protected $mlService;

    public function __construct(HealthMLService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * ML Dashboard with advanced predictions
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $riskAssessment = $this->mlService->predictHealthRisk($user);
        $weightForecast = $this->mlService->forecastWeightML($user);
        $anomalies = $this->mlService->detectHealthAnomalies($user);

        return view('health-ml.dashboard', compact('riskAssessment', 'weightForecast', 'anomalies'));
    }

    /**
     * Get ML risk assessment
     */
    public function getRiskAssessment()
    {
        $user = Auth::user();
        $assessment = $this->mlService->predictHealthRisk($user);
        
        return response()->json($assessment);
    }

    /**
     * Get ML weight forecast
     */
    public function getWeightForecast(Request $request)
    {
        $days = $request->get('days', 30);
        $user = Auth::user();
        
        $forecast = $this->mlService->forecastWeightML($user, $days);
        
        return response()->json($forecast);
    }

    /**
     * Get anomaly detection results
     */
    public function getAnomalies()
    {
        $user = Auth::user();
        $anomalies = $this->mlService->detectHealthAnomalies($user);
        
        return response()->json($anomalies);
    }
}