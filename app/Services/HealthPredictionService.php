<?php

namespace App\Services;

use App\Models\User;
use App\Models\Weight;
use App\Models\Activity;
use Carbon\Carbon;

class HealthPredictionService
{
    /**
     * Predict weight trend for the next 30 days
     */
    public function predictWeightTrend(User $user, int $days = 30): array
    {
        $weights = Weight::where('user_id', $user->id)
            ->orderBy('measured_at')
            ->take(60) // Use last 60 measurements for better accuracy
            ->get();

        if ($weights->count() < 3) {
            return ['status' => 'insufficient_data'];
        }

        // Prepare data for linear regression
        $x = [];
        $y = [];
        $baseDate = $weights->first()->measured_at;

        foreach ($weights as $index => $weight) {
            $x[] = $weight->measured_at->diffInDays($baseDate);
            $y[] = (float) $weight->value_kg;
        }

        // Calculate linear regression
        $regression = $this->linearRegression($x, $y);
        
        // Predict future weights
        $predictions = [];
        $lastDate = $weights->last()->measured_at;
        $lastWeight = (float) $weights->last()->value_kg;
        
        for ($i = 1; $i <= $days; $i++) {
            $futureDate = $lastDate->copy()->addDays($i);
            $daysSinceBase = $futureDate->diffInDays($baseDate);
            $predictedWeight = $regression['slope'] * $daysSinceBase + $regression['intercept'];
            
            $predictions[] = [
                'date' => $futureDate->format('Y-m-d'),
                'predicted_weight' => round($predictedWeight, 2),
                'confidence' => $this->calculateConfidence($regression['r_squared'], $i)
            ];
        }

        return [
            'status' => 'success',
            'current_weight' => $lastWeight,
            'trend_direction' => $regression['slope'] > 0 ? 'increasing' : ($regression['slope'] < 0 ? 'decreasing' : 'stable'),
            'weekly_change_rate' => round($regression['slope'] * 7, 3),
            'monthly_prediction' => end($predictions)['predicted_weight'],
            'confidence_score' => $regression['r_squared'],
            'predictions' => $predictions
        ];
    }

    /**
     * Predict optimal activity level to reach weight goal
     */
    public function predictActivityForWeightGoal(User $user, float $targetWeight, int $timeframeDays = 90): array
    {
        $currentWeight = Weight::where('user_id', $user->id)
            ->orderByDesc('measured_at')
            ->first();

        if (!$currentWeight) {
            return ['status' => 'no_weight_data'];
        }

        $weightDifference = $targetWeight - $currentWeight->value_kg;
        $weeklyWeightChange = $weightDifference / ($timeframeDays / 7);

        // Rough calculation: 1kg = 7700 calories
        $weeklyCalorieDeficit = $weeklyWeightChange * 7700;
        $dailyCalorieDeficit = $weeklyCalorieDeficit / 7;

        // Get current activity level
        $currentActivity = Activity::where('user_id', $user->id)
            ->where('performed_at', '>=', Carbon::now()->subDays(30))
            ->avg('calories') ?? 0;

        $currentWeeklyCalories = $currentActivity * 7;
        $recommendedWeeklyCalories = $currentWeeklyCalories + abs($weeklyCalorieDeficit);
        
        // Convert to activity minutes (rough estimate: 10 calories per minute)
        $recommendedWeeklyMinutes = $recommendedWeeklyCalories / 10;

        return [
            'status' => 'success',
            'current_weight' => $currentWeight->value_kg,
            'target_weight' => $targetWeight,
            'weight_difference' => round($weightDifference, 2),
            'timeframe_days' => $timeframeDays,
            'weekly_calorie_target' => round($recommendedWeeklyCalories),
            'weekly_minutes_target' => round($recommendedWeeklyMinutes),
            'daily_minutes_target' => round($recommendedWeeklyMinutes / 7),
            'current_weekly_calories' => round($currentWeeklyCalories),
            'difficulty' => $this->assessGoalDifficulty($weeklyWeightChange, $timeframeDays)
        ];
    }

    /**
     * Predict sleep quality improvement recommendations
     */
    public function predictSleepImprovement(User $user): array
    {
        $sleepSessions = \App\Models\SleepSession::where('user_id', $user->id)
            ->where('started_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('started_at')
            ->get();

        if ($sleepSessions->count() < 7) {
            return ['status' => 'insufficient_data'];
        }

        $avgDuration = $sleepSessions->avg('duration_minutes');
        $avgQuality = $sleepSessions->avg('quality');
        $consistency = $this->calculateSleepConsistency($sleepSessions);

        $recommendations = [];
        $improvementPotential = 0;

        // Duration recommendations
        if ($avgDuration < 420) { // Less than 7 hours
            $recommendations[] = [
                'category' => 'duration',
                'current' => round($avgDuration / 60, 1),
                'target' => 7.5,
                'improvement' => 'Increase sleep duration',
                'impact' => 'high'
            ];
            $improvementPotential += 30;
        }

        // Quality recommendations
        if ($avgQuality < 4) {
            $recommendations[] = [
                'category' => 'quality',
                'current' => round($avgQuality, 1),
                'target' => 4.5,
                'improvement' => 'Focus on sleep environment and routine',
                'impact' => 'medium'
            ];
            $improvementPotential += 20;
        }

        // Consistency recommendations
        if ($consistency < 70) {
            $recommendations[] = [
                'category' => 'consistency',
                'current' => round($consistency),
                'target' => 85,
                'improvement' => 'Maintain regular sleep schedule',
                'impact' => 'medium'
            ];
            $improvementPotential += 15;
        }

        $predictedQuality = min(5, $avgQuality + ($improvementPotential / 100) * 2);

        return [
            'status' => 'success',
            'current_quality' => round($avgQuality, 1),
            'predicted_quality' => round($predictedQuality, 1),
            'improvement_potential' => $improvementPotential,
            'recommendations' => $recommendations,
            'timeframe_weeks' => 4
        ];
    }

    /**
     * Calculate linear regression
     */
    private function linearRegression(array $x, array $y): array
    {
        $n = count($x);
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        $sumY2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
            $sumY2 += $y[$i] * $y[$i];
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        // Calculate R-squared
        $yMean = $sumY / $n;
        $ssRes = 0;
        $ssTot = 0;

        for ($i = 0; $i < $n; $i++) {
            $predicted = $slope * $x[$i] + $intercept;
            $ssRes += pow($y[$i] - $predicted, 2);
            $ssTot += pow($y[$i] - $yMean, 2);
        }

        $rSquared = $ssTot > 0 ? 1 - ($ssRes / $ssTot) : 0;

        return [
            'slope' => $slope,
            'intercept' => $intercept,
            'r_squared' => max(0, min(1, $rSquared))
        ];
    }

    /**
     * Calculate confidence based on R-squared and prediction distance
     */
    private function calculateConfidence(float $rSquared, int $daysAhead): float
    {
        $baseConfidence = $rSquared * 100;
        $timeDecay = max(0, 1 - ($daysAhead / 100)); // Confidence decreases over time
        return round($baseConfidence * $timeDecay, 1);
    }

    /**
     * Assess goal difficulty
     */
    private function assessGoalDifficulty(float $weeklyWeightChange, int $timeframeDays): string
    {
        $absChange = abs($weeklyWeightChange);
        
        if ($absChange <= 0.25) return 'easy';
        if ($absChange <= 0.5) return 'moderate';
        if ($absChange <= 1.0) return 'challenging';
        return 'very_difficult';
    }

    /**
     * Calculate sleep consistency
     */
    private function calculateSleepConsistency($sleepSessions): float
    {
        $bedtimes = $sleepSessions->map(function ($session) {
            return $session->started_at->format('H') * 60 + $session->started_at->format('i');
        });

        if ($bedtimes->isEmpty()) return 0;

        $avg = $bedtimes->avg();
        $variance = $bedtimes->map(function ($time) use ($avg) {
            return pow($time - $avg, 2);
        })->avg();

        return max(0, 100 - sqrt($variance) / 6);
    }
}