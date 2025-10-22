<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SymptomCheckerController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = env('SYMPTOM_API_URL', 'http://127.0.0.1:8001');
    }

    public function index()
    {
        return view('symptom-checker');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string|min:3|max:1000',
            'age' => 'nullable|integer|min:0|max:120',
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        try {
            $response = Http::timeout(30)->post($this->apiBaseUrl . '/analyze-symptoms', [
                'symptoms' => $request->symptoms,
                'age' => $request->age,
                'gender' => $request->gender,
                'language' => 'en'
            ]);

            if ($response->successful()) {
                $result = $response->json();

                // Store in session for history
                $this->storeInHistory($result);

                return response()->json($result);
            } else {
                return response()->json([
                    'error' => 'Unable to analyze symptoms. Please try again.',
                    'details' => $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Symptom analysis error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Service temporarily unavailable. Please try again later.',
                'details' => $e->getMessage()
            ], 503);
        }
    }

    public function history()
    {
        $history = session('symptom_history', []);
        return view('symptom-history', compact('history'));
    }

    private function storeInHistory($analysis)
    {
        $history = session('symptom_history', []);

        // Keep only last 10 analyses
        array_unshift($history, [
            'query_id' => $analysis['query_id'],
            'symptoms' => request('symptoms'),
            'urgency_level' => $analysis['urgency_level'],
            'urgency_score' => $analysis['urgency_score'],
            'timestamp' => now()->toDateTimeString(),
            'matched_symptoms_count' => count($analysis['matched_symptoms'])
        ]);

        $history = array_slice($history, 0, 10);
        session(['symptom_history' => $history]);
    }
}
