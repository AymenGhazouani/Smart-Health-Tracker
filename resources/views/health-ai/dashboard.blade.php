@extends('layouts.app')

@section('title', 'AI Health Insights')

@section('content')
<div class="metrics-dashboard">
    <header class="dashboard-header">
        <h1>🧠 AI Health Insights</h1>
        <p>Advanced artificial intelligence analysis of your health patterns</p>
    </header>

    <div class="metrics-grid">
                    
        <!-- Health Score Overview -->
        <div class="metric-card ai-overall-card">
            <div class="card-icon">🎯</div>
            <div class="card-content">
                <h3>Overall Health Score</h3>
                <div class="metric-value">{{ $insights['overall_score']['overall'] }}</div>
                <div class="metric-detail">{{ ucfirst($insights['overall_score']['status']) }} Health Status</div>
                <div class="score-breakdown">
                    <div class="score-item">
                        <span>Weight: {{ $insights['overall_score']['weight'] }}</span>
                    </div>
                    <div class="score-item">
                        <span>Activity: {{ $insights['overall_score']['activity'] }}</span>
                    </div>
                    <div class="score-item">
                        <span>Sleep: {{ $insights['overall_score']['sleep'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Health Alerts -->
        @if(!empty($insights['alerts']))
        <div class="metric-card alert-card">
            <div class="card-icon">⚠️</div>
            <div class="card-content">
                <h3>Health Alerts</h3>
                <div class="alerts-list">
                    @foreach($insights['alerts'] as $alert)
                        <div class="alert-item {{ $alert['severity'] === 'high' ? 'high-severity' : 'medium-severity' }}">
                            <strong>{{ ucfirst($alert['type']) }}:</strong> {{ $alert['message'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Weight Analysis -->
        <div class="metric-card weight-analysis-card">
            <div class="card-icon">⚖️</div>
            <div class="card-content">
                <h3>Weight Analysis</h3>
                @if(isset($insights['weight_analysis']['status']) && $insights['weight_analysis']['status'] === 'insufficient_data')
                    <div class="metric-value">No Data</div>
                    <div class="metric-detail">Not enough weight data for analysis</div>
                @else
                    <div class="metric-value">{{ $insights['weight_analysis']['current_weight'] ?? 'N/A' }} kg</div>
                    <div class="metric-detail">
                        Trend: {{ ucfirst($insights['weight_analysis']['status']) }}
                        @if(isset($insights['weight_analysis']['change_from_previous']))
                            <br>Change: {{ $insights['weight_analysis']['change_from_previous'] > 0 ? '+' : '' }}{{ $insights['weight_analysis']['change_from_previous'] }} kg
                        @endif
                    </div>
                @endif
                <a href="{{ route('health-predictions.dashboard') }}" class="card-action">View Predictions</a>
            </div>
        </div>

        <!-- Activity Analysis -->
        <div class="metric-card activity-analysis-card">
            <div class="card-icon">🏃</div>
            <div class="card-content">
                <h3>Activity Analysis</h3>
                @if(isset($insights['activity_analysis']['status']) && $insights['activity_analysis']['status'] === 'no_recent_activity')
                    <div class="metric-value">No Data</div>
                    <div class="metric-detail">No recent activity data found</div>
                @else
                    <div class="metric-value">{{ $insights['activity_analysis']['weekly_minutes'] ?? 0 }} mins</div>
                    <div class="metric-detail">
                        Weekly activity • {{ $insights['activity_analysis']['weekly_calories'] ?? 0 }} calories
                        @if(isset($insights['activity_analysis']['favorite_activity']))
                            <br>Favorite: {{ ucfirst($insights['activity_analysis']['favorite_activity']) }}
                        @endif
                    </div>
                @endif
                <a href="{{ route('metrics.activities') }}" class="card-action">View Activities</a>
            </div>
        </div>

        <!-- Sleep Analysis -->
        <div class="metric-card sleep-analysis-card">
            <div class="card-icon">😴</div>
            <div class="card-content">
                <h3>Sleep Analysis</h3>
                @if(isset($insights['sleep_analysis']['status']) && $insights['sleep_analysis']['status'] === 'no_recent_data')
                    <div class="metric-value">No Data</div>
                    <div class="metric-detail">No recent sleep data found</div>
                @else
                    <div class="metric-value">{{ $insights['sleep_analysis']['average_duration_hours'] ?? 0 }}h</div>
                    <div class="metric-detail">
                        Average sleep • Quality: {{ $insights['sleep_analysis']['average_quality'] ?? 0 }}/5
                        <br>{{ $insights['sleep_analysis']['nights_tracked'] ?? 0 }} nights tracked
                    </div>
                @endif
                <a href="{{ route('metrics.sleep') }}" class="card-action">View Sleep</a>
            </div>
        </div>

        <!-- AI Recommendations -->
        @if(!empty($insights['recommendations']))
        <div class="metric-card recommendations-card">
            <div class="card-icon">💡</div>
            <div class="card-content">
                <h3>AI Recommendations</h3>
                <div class="recommendations-list">
                    @foreach($insights['recommendations'] as $recommendation)
                        <div class="recommendation-item {{ $recommendation['priority'] }}">
                            <strong>{{ ucfirst($recommendation['category']) }}:</strong> {{ $recommendation['message'] }}
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('health-predictions.dashboard') }}" class="card-action">Get More Insights</a>
            </div>
        </div>
        @endif

        <!-- Advanced AI Features -->
        <div class="metric-card ml-features-card">
            <div class="card-icon">🤖</div>
            <div class="card-content">
                <h3>Advanced ML Features</h3>
                <div class="metric-detail">
                    Neural networks, ARIMA forecasting, and anomaly detection using real machine learning algorithms
                </div>
                <div class="feature-list">
                    <div class="feature-item">🧠 Neural Network Risk Assessment</div>
                    <div class="feature-item">📈 ARIMA Time Series Forecasting</div>
                    <div class="feature-item">🔍 Isolation Forest Anomaly Detection</div>
                </div>
                <a href="{{ route('health-ml.dashboard') }}" class="card-action">Explore ML Features</a>
            </div>
        </div>

        <!-- Health Predictions -->
        <div class="metric-card predictions-card">
            <div class="card-icon">🔮</div>
            <div class="card-content">
                <h3>Health Predictions</h3>
                <div class="metric-detail">
                    AI-powered predictions for weight trends and sleep improvement potential
                </div>
                <div class="feature-list">
                    <div class="feature-item">📊 Weight Trend Forecasting</div>
                    <div class="feature-item">🎯 Goal Planning Calculator</div>
                    <div class="feature-item">😴 Sleep Quality Predictions</div>
                </div>
                <a href="{{ route('health-predictions.dashboard') }}" class="card-action">View Predictions</a>
            </div>
        </div>

    </div>
</div>

<style>
/* Use the same styling as the main dashboard */
.metrics-dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.dashboard-header {
    text-align: center;
    margin-bottom: 3rem;
}

.dashboard-header h1 {
    color: var(--pico-primary);
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.dashboard-header p {
    color: var(--pico-muted-color);
    font-size: 1.1rem;
    margin: 0;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.metric-card {
    background: linear-gradient(135deg, var(--pico-background-color) 0%, rgba(var(--pico-primary-rgb), 0.05) 100%);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1.5rem;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.metric-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: var(--pico-primary);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--pico-primary), var(--pico-secondary));
}

.ai-overall-card::before {
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
}

.weight-analysis-card::before {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
}

.activity-analysis-card::before {
    background: linear-gradient(90deg, #10b981, #f59e0b);
}

.sleep-analysis-card::before {
    background: linear-gradient(90deg, #06b6d4, #3b82f6);
}

.alert-card::before {
    background: linear-gradient(90deg, #f59e0b, #ef4444);
}

.recommendations-card::before {
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

.ml-features-card::before {
    background: linear-gradient(90deg, #7c3aed, #a855f7);
}

.predictions-card::before {
    background: linear-gradient(90deg, #059669, #10b981);
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    text-align: center;
}

.card-content h3 {
    color: var(--pico-primary);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-align: center;
}

.metric-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--pico-color);
    text-align: center;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.metric-detail {
    color: var(--pico-muted-color);
    font-size: 0.9rem;
    text-align: center;
    margin-bottom: 1.5rem;
}

.card-action {
    display: block;
    width: 100%;
    padding: 0.75rem 1.5rem;
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
    text-decoration: none;
    border-radius: 0.75rem;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.card-action:hover {
    background: var(--pico-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--pico-primary-rgb), 0.3);
}

/* AI-specific styles */
.score-breakdown {
    display: flex;
    justify-content: space-around;
    margin-bottom: 1rem;
}

.score-item {
    font-size: 0.8rem;
    color: var(--pico-muted-color);
}

.alerts-list, .recommendations-list {
    margin-bottom: 1rem;
}

.alert-item, .recommendation-item {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.9rem;
}

.alert-item.high-severity, .recommendation-item.high {
    background: rgba(239, 68, 68, 0.1);
    border-left: 3px solid #ef4444;
}

.alert-item.medium-severity, .recommendation-item.medium {
    background: rgba(245, 158, 11, 0.1);
    border-left: 3px solid #f59e0b;
}

.recommendation-item.low {
    background: rgba(59, 130, 246, 0.1);
    border-left: 3px solid #3b82f6;
}

.feature-list {
    margin-bottom: 1rem;
}

.feature-item {
    padding: 0.25rem 0;
    font-size: 0.9rem;
    color: var(--pico-muted-color);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .metrics-dashboard {
        padding: 1rem;
    }
    
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .metric-card {
        padding: 1.5rem;
    }
    
    .metric-value {
        font-size: 2rem;
    }
}
</style>
@endsection