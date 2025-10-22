<?php

namespace App\Http\Controllers;

use App\Services\HealthAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthAIController extends Controller
{
    protected $healthAI;

    public function __construct(HealthAIService $healthAI)
    {
        $this->healthAI = $healthAI;
    }

    /**
     * Display AI-powered health dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $insights = $this->healthAI->generateHealthInsights($user);

        return view('health-ai.dashboard', compact('insights'));
    }

    /**
     * Get health insights as JSON (for AJAX requests)
     */
    public function getInsights()
    {
        $user = Auth::user();
        $insights = $this->healthAI->generateHealthInsights($user);

        return response()->json($insights);
    }

    /**
     * Get health score only
     */
    public function getHealthScore()
    {
        $user = Auth::user();
        $score = $this->healthAI->calculateHealthScore($user);

        return response()->json($score);
    }
}