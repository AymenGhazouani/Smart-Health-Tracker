# AI Health Features Documentation

## Overview
This Laravel health metrics project now includes simple AI features that analyze user health data (weight, activity, sleep) without relying on external APIs. All AI logic is implemented locally using mathematical algorithms and rule-based systems.

## Features Implemented

### 1. Health Insights Engine (`HealthAIService`)
**Location:** `app/Services/HealthAIService.php`

**Features:**
- **Health Score Calculator**: Combines weight, activity, and sleep metrics into an overall health score (0-100)
- **Trend Analysis**: Analyzes patterns in weight changes, activity levels, and sleep quality
- **Anomaly Detection**: Identifies unusual patterns like rapid weight changes or poor sleep quality
- **Personalized Recommendations**: Generates actionable health advice based on user data
- **Health Status Classification**: Categorizes health status as excellent, good, fair, or needs improvement

**Key Methods:**
- `generateHealthInsights()` - Main method that returns comprehensive health analysis
- `calculateHealthScore()` - Computes overall health score from all metrics
- `analyzeWeightTrends()` - Detects weight gain/loss patterns using linear regression
- `detectAnomalies()` - Identifies concerning health patterns

### 2. Health Predictions Engine (`HealthPredictionService`)
**Location:** `app/Services/HealthPredictionService.php`

**Features:**
- **Weight Trend Prediction**: Uses linear regression to predict future weight changes
- **Goal Planning**: Calculates required activity levels to reach weight goals
- **Sleep Improvement Predictions**: Analyzes sleep patterns and suggests improvements
- **Confidence Scoring**: Provides confidence levels for predictions based on data quality

**Key Methods:**
- `predictWeightTrend()` - Predicts weight changes for next 30 days
- `predictActivityForWeightGoal()` - Calculates activity needed for weight goals
- `predictSleepImprovement()` - Suggests sleep quality improvements

### 3. AI Dashboard
**Location:** `resources/views/health-ai/dashboard.blade.php`

**Features:**
- Visual health score display with color-coded status
- Detailed analysis breakdown for weight, activity, and sleep
- Health alerts and warnings
- Personalized AI recommendations
- Links to additional AI features

### 4. Health Predictions Dashboard
**Location:** `resources/views/health-predictions/dashboard.blade.php`

**Features:**
- Weight trend predictions with confidence scores
- Sleep improvement potential analysis
- Interactive weight goal calculator
- Activity planning recommendations

### 5. AI Widget for Metrics Dashboard
**Location:** `resources/views/components/ai-health-widget.blade.php`

**Features:**
- Compact health score display
- Alert and recommendation counters
- Quick access to full AI dashboard

## AI Algorithms Used

### 1. Linear Regression
Used for weight trend analysis and predictions:
```php
// Calculates slope and intercept for trend line
$slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
$intercept = ($sumY - $slope * $sumX) / $n;
```

### 2. Statistical Analysis
- **Variance Calculation**: For consistency scoring
- **Moving Averages**: For trend smoothing
- **Standard Deviation**: For anomaly detection

### 3. Rule-Based Scoring
Health scores based on established health guidelines:
- **Sleep**: 7-9 hours optimal, quality rating 1-5
- **Activity**: WHO recommendation of 150 minutes/week
- **Weight**: Stability preferred over rapid changes

### 4. Confidence Scoring
Predictions include confidence levels based on:
- Data quality (R-squared values)
- Time distance (confidence decreases over time)
- Data quantity (more data = higher confidence)

## Routes Added

```php
// AI Insights
Route::get('/health-ai', [HealthAIController::class, 'dashboard']);
Route::get('/health-ai/insights', [HealthAIController::class, 'getInsights']);
Route::get('/health-ai/score', [HealthAIController::class, 'getHealthScore']);

// Health Predictions
Route::get('/health-predictions', [HealthPredictionController::class, 'dashboard']);
Route::post('/health-predictions/weight-goal', [HealthPredictionController::class, 'weightGoal']);
Route::get('/health-predictions/weight-trend', [HealthPredictionController::class, 'weightTrend']);
```

## Usage Examples

### 1. Get Health Insights
```php
$healthAI = new HealthAIService();
$insights = $healthAI->generateHealthInsights($user);
// Returns comprehensive health analysis
```

### 2. Predict Weight Trend
```php
$predictionService = new HealthPredictionService();
$prediction = $predictionService->predictWeightTrend($user, 30);
// Predicts weight for next 30 days
```

### 3. Calculate Goal Plan
```php
$goalPlan = $predictionService->predictActivityForWeightGoal($user, 70, 90);
// Calculates activity needed to reach 70kg in 90 days
```

## Integration Points

1. **Metrics Dashboard**: AI widget automatically appears alongside existing metrics
2. **Navigation**: AI features accessible through dedicated routes
3. **Data Integration**: Uses existing Weight, Activity, and SleepSession models
4. **User Context**: All AI features are user-specific and require authentication

## Technical Benefits

1. **No External Dependencies**: All AI logic runs locally
2. **Privacy Focused**: User data never leaves your server
3. **Customizable**: Easy to modify algorithms and scoring criteria
4. **Scalable**: Efficient algorithms that work with growing datasets
5. **Educational**: Clear, understandable AI implementations

## Future Enhancement Ideas

1. **Machine Learning**: Add simple ML models for better predictions
2. **Nutrition Integration**: Include meal data in health scoring
3. **Exercise Recommendations**: Suggest specific activities based on goals
4. **Health Risk Assessment**: Identify potential health risks early
5. **Comparative Analysis**: Compare user metrics with population averages

## Data Requirements

- **Minimum for Weight Predictions**: 3 weight measurements
- **Minimum for Sleep Analysis**: 7 sleep sessions
- **Minimum for Activity Analysis**: Recent activity data (30 days)
- **Optimal Data**: 30+ days of consistent logging across all metrics

This AI implementation provides a solid foundation for health analytics while remaining simple, transparent, and fully under your control.