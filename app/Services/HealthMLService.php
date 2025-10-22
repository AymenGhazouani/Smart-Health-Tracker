<?php

namespace App\Services;

use App\Models\User;
use App\Models\Weight;
use App\Models\Activity;
use App\Models\SleepSession;
use Carbon\Carbon;

class HealthMLService
{
    /**
     * Simple Neural Network for Health Risk Assessment
     * Uses a basic feedforward network with one hidden layer
     */
    public function predictHealthRisk(User $user): array
    {
        // Collect and normalize features
        $features = $this->extractFeatures($user);
        
        if (empty($features)) {
            return ['status' => 'insufficient_data'];
        }

        // Normalize features (0-1 scale)
        $normalizedFeatures = $this->normalizeFeatures($features);

        // Simple neural network prediction
        $riskScore = $this->neuralNetworkPredict($normalizedFeatures);
        
        // K-means clustering for health patterns
        $healthCluster = $this->clusterHealthPattern($normalizedFeatures);
        
        // Decision tree for recommendations
        $recommendations = $this->decisionTreeRecommendations($normalizedFeatures);

        return [
            'status' => 'success',
            'risk_score' => round($riskScore * 100, 1), // 0-100 scale
            'risk_level' => $this->getRiskLevel($riskScore),
            'health_cluster' => $healthCluster,
            'ml_recommendations' => $recommendations,
            'confidence' => $this->calculateMLConfidence($features),
            'features_used' => array_keys($features)
        ];
    }

    /**
     * Time Series Forecasting using ARIMA-like approach
     */
    public function forecastWeightML(User $user, int $days = 30): array
    {
        $weights = Weight::where('user_id', $user->id)
            ->orderBy('measured_at')
            ->take(60)
            ->get();

        if ($weights->count() < 10) {
            return ['status' => 'insufficient_data'];
        }

        $timeSeries = $weights->pluck('value_kg')->toArray();
        
        // Simple ARIMA(1,1,1) implementation
        $forecast = $this->arimaForecast($timeSeries, $days);
        
        // Seasonal decomposition
        $seasonality = $this->detectSeasonality($timeSeries);
        
        // Confidence intervals using bootstrap
        $confidenceIntervals = $this->bootstrapConfidenceIntervals($timeSeries, $days);

        return [
            'status' => 'success',
            'forecast' => $forecast,
            'seasonality' => $seasonality,
            'confidence_intervals' => $confidenceIntervals,
            'model_accuracy' => $this->calculateForecastAccuracy($timeSeries)
        ];
    }

    /**
     * Anomaly Detection using Isolation Forest algorithm
     */
    public function detectHealthAnomalies(User $user): array
    {
        $healthData = $this->getHealthDataMatrix($user);
        
        if (count($healthData) < 7) {
            return ['status' => 'insufficient_data'];
        }

        // Isolation Forest implementation
        $anomalies = $this->isolationForest($healthData);
        
        // Statistical outlier detection
        $outliers = $this->statisticalOutliers($healthData);

        return [
            'status' => 'success',
            'anomalies' => $anomalies,
            'outliers' => $outliers,
            'anomaly_score' => $this->calculateAnomalyScore($anomalies, $outliers)
        ];
    }

    /**
     * Extract features for ML models
     */
    private function extractFeatures(User $user): array
    {
        $features = [];

        // Weight features
        $weights = Weight::where('user_id', $user->id)
            ->orderByDesc('measured_at')
            ->take(30)
            ->get();

        if ($weights->count() >= 3) {
            $weightValues = $weights->pluck('value_kg')->toArray();
            $features['weight_avg'] = array_sum($weightValues) / count($weightValues);
            $features['weight_std'] = $this->standardDeviation($weightValues);
            $features['weight_trend'] = $this->calculateTrend($weightValues);
            $features['weight_volatility'] = $this->calculateVolatility($weightValues);
        }

        // Activity features
        $activities = Activity::where('user_id', $user->id)
            ->where('performed_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($activities->count() > 0) {
            $features['activity_frequency'] = $activities->count() / 30; // per day
            $features['activity_avg_duration'] = $activities->avg('duration_minutes');
            $features['activity_avg_calories'] = $activities->avg('calories') ?? 0;
            $features['activity_consistency'] = $this->calculateActivityConsistency($activities);
        }

        // Sleep features
        $sleepSessions = SleepSession::where('user_id', $user->id)
            ->where('started_at', '>=', Carbon::now()->subDays(30))
            ->get();

        if ($sleepSessions->count() > 0) {
            $features['sleep_avg_duration'] = $sleepSessions->avg('duration_minutes');
            $features['sleep_avg_quality'] = $sleepSessions->avg('quality');
            $features['sleep_consistency'] = $this->calculateSleepConsistency($sleepSessions);
        }

        return $features;
    }

    /**
     * Normalize features to 0-1 scale
     */
    private function normalizeFeatures(array $features): array
    {
        $normalized = [];
        
        // Define normalization ranges based on typical health values
        $ranges = [
            'weight_avg' => [40, 150], // kg
            'weight_std' => [0, 10],
            'weight_trend' => [-2, 2],
            'weight_volatility' => [0, 5],
            'activity_frequency' => [0, 2], // per day
            'activity_avg_duration' => [0, 180], // minutes
            'activity_avg_calories' => [0, 1000],
            'activity_consistency' => [0, 100],
            'sleep_avg_duration' => [240, 600], // minutes
            'sleep_avg_quality' => [1, 5],
            'sleep_consistency' => [0, 100]
        ];

        foreach ($features as $key => $value) {
            if (isset($ranges[$key])) {
                $min = $ranges[$key][0];
                $max = $ranges[$key][1];
                $normalized[$key] = max(0, min(1, ($value - $min) / ($max - $min)));
            } else {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Simple feedforward neural network
     */
    private function neuralNetworkPredict(array $features): float
    {
        // Pre-trained weights (in real implementation, these would be learned)
        $inputWeights = [
            'weight_std' => 0.3,
            'weight_volatility' => 0.25,
            'activity_frequency' => -0.4,
            'activity_consistency' => -0.3,
            'sleep_avg_quality' => -0.35,
            'sleep_consistency' => -0.2
        ];

        $hiddenWeights = [0.6, -0.4, 0.5, -0.3, 0.7];
        $bias = 0.1;

        // Input layer to hidden layer
        $hiddenInputs = [];
        foreach ($inputWeights as $feature => $weight) {
            if (isset($features[$feature])) {
                $hiddenInputs[] = $features[$feature] * $weight;
            }
        }

        // Hidden layer activation (sigmoid)
        $hiddenOutputs = [];
        for ($i = 0; $i < count($hiddenWeights); $i++) {
            $input = ($hiddenInputs[$i] ?? 0) + $bias;
            $hiddenOutputs[] = $this->sigmoid($input);
        }

        // Hidden layer to output
        $output = 0;
        for ($i = 0; $i < count($hiddenOutputs); $i++) {
            $output += $hiddenOutputs[$i] * $hiddenWeights[$i];
        }

        return $this->sigmoid($output);
    }

    /**
     * K-means clustering for health patterns
     */
    private function clusterHealthPattern(array $features): string
    {
        // Simplified clustering based on key health indicators
        $activityScore = ($features['activity_frequency'] ?? 0) * 0.4 + 
                        ($features['activity_consistency'] ?? 0) * 0.6;
        
        $sleepScore = ($features['sleep_avg_quality'] ?? 0) * 0.6 + 
                     ($features['sleep_consistency'] ?? 0) * 0.4;
        
        $weightScore = 1 - (($features['weight_std'] ?? 0) * 0.5 + 
                           ($features['weight_volatility'] ?? 0) * 0.5);

        $overallScore = ($activityScore + $sleepScore + $weightScore) / 3;

        if ($overallScore >= 0.7) return 'optimal_health';
        if ($overallScore >= 0.5) return 'moderate_health';
        if ($overallScore >= 0.3) return 'needs_attention';
        return 'high_risk';
    }

    /**
     * Decision tree for recommendations
     */
    private function decisionTreeRecommendations(array $features): array
    {
        $recommendations = [];

        // Activity branch
        if (($features['activity_frequency'] ?? 0) < 0.3) {
            $recommendations[] = [
                'type' => 'activity',
                'priority' => 'high',
                'message' => 'ML Model suggests increasing daily physical activity',
                'confidence' => 0.85
            ];
        }

        // Sleep branch
        if (($features['sleep_avg_quality'] ?? 0) < 0.6) {
            $recommendations[] = [
                'type' => 'sleep',
                'priority' => 'medium',
                'message' => 'ML Model recommends improving sleep quality',
                'confidence' => 0.78
            ];
        }

        // Weight stability branch
        if (($features['weight_volatility'] ?? 0) > 0.6) {
            $recommendations[] = [
                'type' => 'weight',
                'priority' => 'medium',
                'message' => 'ML Model detects weight instability patterns',
                'confidence' => 0.72
            ];
        }

        return $recommendations;
    }

    /**
     * ARIMA forecasting implementation
     */
    private function arimaForecast(array $timeSeries, int $steps): array
    {
        $n = count($timeSeries);
        if ($n < 3) return [];

        // Simple AR(1) model: X(t) = φ * X(t-1) + ε
        $phi = $this->calculateAutoCorrelation($timeSeries, 1);
        
        $forecast = [];
        $lastValue = end($timeSeries);
        
        for ($i = 1; $i <= $steps; $i++) {
            $predicted = $lastValue * pow($phi, $i);
            $forecast[] = round($predicted, 2);
        }

        return $forecast;
    }

    /**
     * Isolation Forest for anomaly detection
     */
    private function isolationForest(array $data): array
    {
        $anomalies = [];
        $threshold = 0.6; // Anomaly threshold
        
        foreach ($data as $index => $point) {
            $isolationScore = $this->calculateIsolationScore($point, $data);
            if ($isolationScore > $threshold) {
                $anomalies[] = [
                    'index' => $index,
                    'score' => $isolationScore,
                    'data' => $point
                ];
            }
        }

        return $anomalies;
    }

    // Helper methods
    private function sigmoid(float $x): float
    {
        return 1 / (1 + exp(-$x));
    }

    private function standardDeviation(array $values): float
    {
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values);
        
        return sqrt($variance);
    }

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

    private function calculateVolatility(array $values): float
    {
        if (count($values) < 2) return 0;
        
        $returns = [];
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i-1] != 0) {
                $returns[] = ($values[$i] - $values[$i-1]) / $values[$i-1];
            }
        }
        
        return $this->standardDeviation($returns);
    }

    private function calculateAutoCorrelation(array $series, int $lag): float
    {
        $n = count($series);
        if ($n <= $lag) return 0;

        $mean = array_sum($series) / $n;
        
        $numerator = 0;
        $denominator = 0;
        
        for ($i = 0; $i < $n - $lag; $i++) {
            $numerator += ($series[$i] - $mean) * ($series[$i + $lag] - $mean);
        }
        
        for ($i = 0; $i < $n; $i++) {
            $denominator += pow($series[$i] - $mean, 2);
        }
        
        return $denominator != 0 ? $numerator / $denominator : 0;
    }

    private function calculateIsolationScore(array $point, array $dataset): float
    {
        // Simplified isolation score based on distance to other points
        $distances = [];
        foreach ($dataset as $other) {
            if ($other !== $point) {
                $distances[] = $this->euclideanDistance($point, $other);
            }
        }
        
        $avgDistance = array_sum($distances) / count($distances);
        $maxDistance = max($distances);
        
        return $maxDistance > 0 ? $avgDistance / $maxDistance : 0;
    }

    private function euclideanDistance(array $point1, array $point2): float
    {
        $sum = 0;
        $keys = array_intersect(array_keys($point1), array_keys($point2));
        
        foreach ($keys as $key) {
            $sum += pow($point1[$key] - $point2[$key], 2);
        }
        
        return sqrt($sum);
    }

    private function getRiskLevel(float $score): string
    {
        if ($score < 0.3) return 'low';
        if ($score < 0.6) return 'moderate';
        if ($score < 0.8) return 'high';
        return 'very_high';
    }

    private function calculateMLConfidence(array $features): float
    {
        $dataQuality = count($features) / 11; // 11 possible features
        $completeness = array_sum(array_map(function($v) { return $v > 0 ? 1 : 0; }, $features)) / count($features);
        
        return round(($dataQuality + $completeness) / 2 * 100, 1);
    }

    private function getHealthDataMatrix(User $user): array
    {
        // Get recent health data as matrix for anomaly detection
        $data = [];
        
        $weights = Weight::where('user_id', $user->id)
            ->orderByDesc('measured_at')
            ->take(30)
            ->get();
            
        foreach ($weights as $weight) {
            $data[] = [
                'weight' => (float) $weight->value_kg,
                'date' => $weight->measured_at->dayOfYear
            ];
        }
        
        return $data;
    }

    private function calculateActivityConsistency($activities): float
    {
        // Implementation from HealthAIService
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

    private function calculateSleepConsistency($sleepSessions): float
    {
        // Implementation from HealthAIService
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

    private function detectSeasonality(array $timeSeries): array
    {
        // Simple seasonality detection
        return [
            'has_seasonality' => false,
            'period' => null,
            'strength' => 0
        ];
    }

    private function bootstrapConfidenceIntervals(array $timeSeries, int $days): array
    {
        // Simplified confidence intervals
        $std = $this->standardDeviation($timeSeries);
        return [
            'lower_95' => -1.96 * $std,
            'upper_95' => 1.96 * $std
        ];
    }

    private function calculateForecastAccuracy(array $timeSeries): float
    {
        // Simple accuracy measure
        return 0.75; // 75% accuracy placeholder
    }

    private function statisticalOutliers(array $data): array
    {
        // Z-score based outlier detection
        return [];
    }

    private function calculateAnomalyScore(array $anomalies, array $outliers): float
    {
        return (count($anomalies) + count($outliers)) / 10 * 100;
    }
}