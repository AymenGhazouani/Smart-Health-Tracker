<?php

namespace App\Services;

use App\Models\User;
use App\Models\Weight;
use App\Models\Activity;
use App\Models\SleepSession;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HealthAIService
{
    /**
     * Generate comprehensive health insights for a user
     */
    public function generateHealthInsights(User $user): array
    {
        $insights = [
            'overall_score' => $this->calculateHealthScore($user),
            'weight_analysis' => $this->analyzeWeightTrends($user),
            'activity_analysis' => $this->analyzeActivityPatterns($user),
            'sleep_analysis' => $this->analyzeSleepPatterns($user),
            'recommendations' => $this->generateRecommendations($user),
            'alerts' => $this->detectAnomalies($user)
        ];

        return $insights;
    }

    /**
     * Calculate overall health score (0-100)
     */
    public function calculateHealthScore(User $user): array
    {
        $weightScore = $this->getWeightScore($user);
        $activityScore = $this->getActivityScore($user);
        $sleepScore = $this->getSleepScore($user);

        $overallScore = ($weightScore + $activityScore + $sleepScore) / 3;

        return [
            'overall' => round($overallScore, 1),
            'weight' => $weightScore,
            'activity' => $activityScore,
            'sleep' => $sleepScore,
            'status' => $this->getScoreStatus($overallScore)
        ];
    }

    /**
     * Analyze weight trends
     */
    public function analyzeWeightTrends(User $user): array
    {
        $weights = Weight::where('user_id', $user->id)
            ->orderBy('measured_at')
            ->take(30)
            ->get();

        if ($weights->count() < 2) {
            return ['status' => 'insufficient_data'];
        }

        $trend = $this->calculateTrend($weights->pluck('value_kg')->toArray());
        $latestWeight = $weights->last()->value_kg;
        $previousWeight = $weights->count() > 1 ? $weights->get($weights->count() - 2)->value_kg : $latestWeight;
        
        return [
            'trend' => $trend,
            'current_weight' => $latestWeight,
            'change_from_previous' => round($latestWeight - $previousWeight, 2),
            'weekly_average_change' => $this->getWeeklyWeightChange($weights),
            'status' => $this->getWeightStatus($trend)
        ];
    }

    /**
     * Analyze activity patterns
     */
    public function analyzeActivityPatterns(User $user): array
    {
        $activities = Activity::where('user_id', $user->id)
            ->where('performed_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($activities->isEmpty()) {
            return ['status' => 'no_recent_activity'];
        }

        $weeklyMinutes = $activities->sum('duration_minutes') / 4; // 4 weeks
        $weeklyCalories = $activities->sum('calories') / 4;
        $mostCommonActivity = $activities->groupBy('type')->sortByDesc->count()->keys()->first();

        return [
            'weekly_minutes' => round($weeklyMinutes),
            'weekly_calories' => round($weeklyCalories),
            'sessions_per_week' => round($activities->count() / 4, 1),
            'favorite_activity' => $mostCommonActivity,
            'consistency_score' => $this->calculateActivityConsistency($activities),
            'status' => $this->getActivityStatus($weeklyMinutes)
        ];
    }

    /**
     * Analyze sleep patterns
     */
    public function analyzeSleepPatterns(User $user): array
    {
        $sleepSessions = SleepSession::where('user_id', $user->id)
            ->where('started_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($sleepSessions->isEmpty()) {
            return ['status' => 'no_recent_data'];
        }

        $avgDuration = $sleepSessions->avg('duration_minutes');
        $avgQuality = $sleepSessions->avg('quality');
        $consistency = $this->calculateSleepConsistency($sleepSessions);

        return [
            'average_duration_hours' => round($avgDuration / 60, 1),
            'average_quality' => round($avgQuality, 1),
            'consistency_score' => $consistency,
            'nights_tracked' => $sleepSessions->count(),
            'status' => $this->getSleepStatus($avgDuration, $avgQuality)
        ];
    }

    /**
     * Generate personalized recommendations
     */
    public function generateRecommendations(User $user): array
    {
        $recommendations = [];
        
        $weightAnalysis = $this->analyzeWeightTrends($user);
        $activityAnalysis = $this->analyzeActivityPatterns($user);
        $sleepAnalysis = $this->analyzeSleepPatterns($user);

        // Weight recommendations
        if (isset($weightAnalysis['trend'])) {
            if ($weightAnalysis['trend'] > 0.5) {
                $recommendations[] = [
                    'category' => 'weight',
                    'priority' => 'medium',
                    'message' => 'Consider increasing physical activity or reviewing your diet as your weight is trending upward.'
                ];
            } elseif ($weightAnalysis['trend'] < -0.5) {
                $recommendations[] = [
                    'category' => 'weight',
                    'priority' => 'low',
                    'message' => 'Great job! Your weight is trending downward. Keep up the good work!'
                ];
            }
        }

        // Activity recommendations
        if (isset($activityAnalysis['weekly_minutes']) && $activityAnalysis['weekly_minutes'] < 150) {
            $recommendations[] = [
                'category' => 'activity',
                'priority' => 'high',
                'message' => 'Try to increase your weekly activity to at least 150 minutes for optimal health benefits.'
            ];
        }

        // Sleep recommendations
        if (isset($sleepAnalysis['average_duration_hours']) && $sleepAnalysis['average_duration_hours'] < 7) {
            $recommendations[] = [
                'category' => 'sleep',
                'priority' => 'high',
                'message' => 'Aim for 7-9 hours of sleep per night for better recovery and health.'
            ];
        }

        return $recommendations;
    }

    /**
     * Detect health anomalies
     */
    public function detectAnomalies(User $user): array
    {
        $alerts = [];

        // Check for rapid weight changes
        $recentWeights = Weight::where('user_id', $user->id)
            ->orderByDesc('measured_at')
            ->take(7)
            ->get();

        if ($recentWeights->count() >= 2) {
            $weightChange = abs($recentWeights->first()->value_kg - $recentWeights->last()->value_kg);
            if ($weightChange > 2) { // More than 2kg change in a week
                $alerts[] = [
                    'type' => 'weight_change',
                    'severity' => 'medium',
                    'message' => 'Significant weight change detected in the past week.'
                ];
            }
        }

        // Check for irregular sleep
        $recentSleep = SleepSession::where('user_id', $user->id)
            ->where('started_at', '>=', Carbon::now()->subDays(7))
            ->get();

        if ($recentSleep->count() >= 3) {
            $avgQuality = $recentSleep->avg('quality');
            if ($avgQuality < 3) {
                $alerts[] = [
                    'type' => 'poor_sleep',
                    'severity' => 'medium',
                    'message' => 'Your sleep quality has been below average recently.'
                ];
            }
        }

        return $alerts;
    }

    // Helper methods
    private function calculateTrend(array $values): float
    {
        $n = count($values);
        if ($n < 2) return 0;

        $x = range(1, $n);
        $sumX = array_sum($x);
        $sumY = array_sum($values);
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $values[$i];
            $sumX2 += $x[$i] * $x[$i];
        }

        return ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
    }

    private function getWeightScore(User $user): float
    {
        $weights = Weight::where('user_id', $user->id)
            ->orderByDesc('measured_at')
            ->take(10)
            ->get();

        if ($weights->isEmpty()) return 50;

        $trend = $this->calculateTrend($weights->pluck('value_kg')->toArray());
        
        // Score based on stability (closer to 0 trend is better)
        return max(0, min(100, 80 - abs($trend) * 20));
    }

    private function getActivityScore(User $user): float
    {
        $weeklyMinutes = Activity::where('user_id', $user->id)
            ->where('performed_at', '>=', Carbon::now()->subDays(7))
            ->sum('duration_minutes');

        // WHO recommends 150 minutes per week
        return min(100, ($weeklyMinutes / 150) * 100);
    }

    private function getSleepScore(User $user): float
    {
        $recentSleep = SleepSession::where('user_id', $user->id)
            ->where('started_at', '>=', Carbon::now()->subDays(7))
            ->get();

        if ($recentSleep->isEmpty()) return 50;

        $avgDuration = $recentSleep->avg('duration_minutes');
        $avgQuality = $recentSleep->avg('quality');

        // Optimal sleep: 7-9 hours (420-540 minutes)
        $durationScore = 100;
        if ($avgDuration < 420) $durationScore = ($avgDuration / 420) * 100;
        if ($avgDuration > 540) $durationScore = max(0, 100 - (($avgDuration - 540) / 60) * 10);

        $qualityScore = ($avgQuality / 5) * 100;

        return ($durationScore + $qualityScore) / 2;
    }

    private function getScoreStatus(float $score): string
    {
        if ($score >= 80) return 'excellent';
        if ($score >= 60) return 'good';
        if ($score >= 40) return 'fair';
        return 'needs_improvement';
    }

    private function getWeightStatus(float $trend): string
    {
        if (abs($trend) < 0.1) return 'stable';
        return $trend > 0 ? 'increasing' : 'decreasing';
    }

    private function getActivityStatus(float $weeklyMinutes): string
    {
        if ($weeklyMinutes >= 150) return 'excellent';
        if ($weeklyMinutes >= 75) return 'moderate';
        return 'low';
    }

    private function getSleepStatus(float $avgDuration, float $avgQuality): string
    {
        $hours = $avgDuration / 60;
        if ($hours >= 7 && $hours <= 9 && $avgQuality >= 4) return 'excellent';
        if ($hours >= 6 && $avgQuality >= 3) return 'good';
        return 'needs_improvement';
    }

    private function calculateActivityConsistency(Collection $activities): float
    {
        // Calculate how evenly distributed activities are across weeks
        $weeklyDistribution = $activities->groupBy(function ($activity) {
            return $activity->performed_at->format('Y-W');
        })->map->count();

        if ($weeklyDistribution->isEmpty()) return 0;

        $avg = $weeklyDistribution->avg();
        $variance = $weeklyDistribution->map(function ($count) use ($avg) {
            return pow($count - $avg, 2);
        })->avg();

        return max(0, 100 - sqrt($variance) * 10);
    }

    private function calculateSleepConsistency(Collection $sleepSessions): float
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

    private function getWeeklyWeightChange(Collection $weights): float
    {
        if ($weights->count() < 7) return 0;

        $recent = $weights->take(-7);
        $older = $weights->take(-14)->take(7);

        if ($older->isEmpty()) return 0;

        return $recent->avg('value_kg') - $older->avg('value_kg');
    }
}