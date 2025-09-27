@extends('layouts.app')

@section('title', 'Health Metrics')

@section('content')
<div class="metrics-dashboard">
    <header class="dashboard-header">
        <h1>Your Health Dashboard</h1>
        <p>Track your wellness journey with detailed health metrics</p>
    </header>

    <div class="metrics-grid">
        <div class="metric-card weight-card">
            <div class="card-icon">⚖️</div>
            <div class="card-content">
                <h3>Latest Weight</h3>
                <div class="metric-value">
                    {{ $latestWeight? number_format((float)$latestWeight->value_kg, 2).' kg' : 'No data' }}
                </div>
                @if($latestWeight)
                    <div class="metric-detail">
                        Measured {{ $latestWeight->measured_at->diffForHumans() }}
                    </div>
                @endif
                <a href="{{ route('metrics.weights') }}" class="card-action">View Details</a>
            </div>
        </div>

        <div class="metric-card sleep-card">
            <div class="card-icon">😴</div>
            <div class="card-content">
                <h3>Average Sleep (7 days)</h3>
                <div class="metric-value">
                    {{ $avgSleepMins ? $avgSleepMins.' mins' : 'No data' }}
                </div>
                @if($avgSleepMins)
                    <div class="metric-detail">
                        {{ floor($avgSleepMins / 60) }}h {{ $avgSleepMins % 60 }}m average
                    </div>
                @endif
                <a href="{{ route('metrics.sleep') }}" class="card-action">View Details</a>
            </div>
        </div>

        <div class="metric-card activity-card">
            <div class="card-icon">🏃</div>
            <div class="card-content">
                <h3>Activity (7 days)</h3>
                <div class="metric-value">
                    {{ optional($activityTotals)->minutes ?? 0 }} mins
                </div>
                @if(optional($activityTotals)->calories)
                    <div class="metric-detail">
                        {{ $activityTotals->calories }} calories burned
                    </div>
                @endif
                <a href="{{ route('metrics.activities') }}" class="card-action">View Details</a>
            </div>
        </div>
    </div>
</div>

<style>
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

.weight-card::before {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
}

.sleep-card::before {
    background: linear-gradient(90deg, #06b6d4, #3b82f6);
}

.activity-card::before {
    background: linear-gradient(90deg, #10b981, #f59e0b);
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


