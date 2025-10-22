@extends('layouts.app')

@section('title', 'Health Predictions')

@section('content')
<div class="metrics-dashboard">
    <header class="dashboard-header">
        <h1>🔮 Health Predictions & Goals</h1>
        <p>AI-powered forecasting and personalized goal planning</p>
    </header>

    <div class="metrics-grid">
                    
                    <div class="row">
                        <!-- Weight Prediction -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Weight Trend Prediction</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($weightPrediction['status']) && $weightPrediction['status'] === 'insufficient_data')
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Not enough weight data for predictions. Please log at least 3 weight measurements.
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <strong>Current Weight:</strong> {{ $weightPrediction['current_weight'] }} kg
                                        </div>
                                        <div class="mb-3">
                                            <strong>Trend:</strong> 
                                            <span class="badge badge-{{ $weightPrediction['trend_direction'] === 'stable' ? 'success' : ($weightPrediction['trend_direction'] === 'increasing' ? 'warning' : 'info') }}">
                                                {{ ucfirst($weightPrediction['trend_direction']) }}
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Weekly Change Rate:</strong> {{ $weightPrediction['weekly_change_rate'] > 0 ? '+' : '' }}{{ $weightPrediction['weekly_change_rate'] }} kg/week
                                        </div>
                                        <div class="mb-3">
                                            <strong>30-Day Prediction:</strong> {{ $weightPrediction['monthly_prediction'] }} kg
                                        </div>
                                        <div class="mb-3">
                                            <strong>Confidence:</strong> 
                                            <div class="progress">
                                                <div class="progress-bar" style="width: {{ $weightPrediction['confidence_score'] * 100 }}%">
                                                    {{ round($weightPrediction['confidence_score'] * 100) }}%
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sleep Improvement -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-bed"></i> Sleep Improvement Potential</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($sleepPrediction['status']) && $sleepPrediction['status'] === 'insufficient_data')
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Not enough sleep data for predictions. Please log at least 7 sleep sessions.
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <strong>Current Quality:</strong> {{ $sleepPrediction['current_quality'] }}/5
                                        </div>
                                        <div class="mb-3">
                                            <strong>Predicted Quality:</strong> {{ $sleepPrediction['predicted_quality'] }}/5
                                        </div>
                                        <div class="mb-3">
                                            <strong>Improvement Potential:</strong> {{ $sleepPrediction['improvement_potential'] }}%
                                        </div>
                                        
                                        @if(!empty($sleepPrediction['recommendations']))
                                            <h6>Recommendations:</h6>
                                            @foreach($sleepPrediction['recommendations'] as $rec)
                                                <div class="alert alert-{{ $rec['impact'] === 'high' ? 'danger' : 'info' }} py-2">
                                                    <small>
                                                        <strong>{{ ucfirst($rec['category']) }}:</strong> {{ $rec['improvement'] }}
                                                        <br>Current: {{ $rec['current'] }} → Target: {{ $rec['target'] }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weight Goal Calculator -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-bullseye"></i> Weight Goal Calculator</h5>
                                </div>
                                <div class="card-body">
                                    <form id="weightGoalForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="target_weight">Target Weight (kg)</label>
                                                    <input type="number" class="form-control" id="target_weight" name="target_weight" step="0.1" min="30" max="300" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="timeframe_days">Timeframe (days)</label>
                                                    <select class="form-control" id="timeframe_days" name="timeframe_days" required>
                                                        <option value="30">1 Month</option>
                                                        <option value="60">2 Months</option>
                                                        <option value="90" selected>3 Months</option>
                                                        <option value="180">6 Months</option>
                                                        <option value="365">1 Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-success btn-block">Calculate Plan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div id="goalResults" class="mt-4" style="display: none;">
                                        <div class="alert alert-info">
                                            <div id="goalResultsContent"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

.card {
    background: linear-gradient(135deg, var(--pico-background-color) 0%, rgba(var(--pico-primary-rgb), 0.05) 100%);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1.5rem;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: var(--pico-primary);
}

.card-header {
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
    padding: 1rem 1.5rem;
    margin: -2rem -2rem 1.5rem -2rem;
    border-radius: 1.5rem 1.5rem 0 0;
    font-weight: 600;
}

.btn {
    padding: 0.75rem 1.5rem;
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
    text-decoration: none;
    border-radius: 0.75rem;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: inline-block;
}

.btn:hover {
    background: var(--pico-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--pico-primary-rgb), 0.3);
    color: var(--pico-primary-inverse);
}

.btn-block {
    display: block;
    width: 100%;
}

.progress {
    background: rgba(var(--pico-primary-rgb), 0.1);
    border-radius: 0.5rem;
    height: 1.5rem;
    overflow: hidden;
}

.progress-bar {
    background: var(--pico-primary);
    height: 100%;
    transition: width 0.6s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pico-primary-inverse);
    font-weight: 600;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success { background: #10b981; color: white; }
.badge-warning { background: #f59e0b; color: white; }
.badge-danger { background: #ef4444; color: white; }
.badge-info { background: #3b82f6; color: white; }

.alert {
    padding: 1rem;
    border-radius: 0.75rem;
    margin-bottom: 1rem;
    border-left: 4px solid;
}

.alert-info {
    background: rgba(59, 130, 246, 0.1);
    border-left-color: #3b82f6;
    color: #1e40af;
}

.alert-warning {
    background: rgba(245, 158, 11, 0.1);
    border-left-color: #f59e0b;
    color: #92400e;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border-left-color: #ef4444;
    color: #991b1b;
}

.form-control {
    padding: 0.75rem;
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 0.5rem;
    background: var(--pico-background-color);
    color: var(--pico-color);
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: var(--pico-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--pico-primary-rgb), 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .metrics-dashboard {
        padding: 1rem;
    }
    
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .card {
        padding: 1.5rem;
    }
}
</style>

<script>
document.getElementById('weightGoalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('{{ route("health-predictions.weight-goal") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const resultsDiv = document.getElementById('goalResultsContent');
            const difficulty = data.difficulty;
            const difficultyClass = difficulty === 'easy' ? 'success' : 
                                  difficulty === 'moderate' ? 'warning' : 
                                  difficulty === 'challenging' ? 'danger' : 'dark';
            
            resultsDiv.innerHTML = `
                <h6>Your Weight Goal Plan</h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Weight Change:</strong> ${data.weight_difference > 0 ? '+' : ''}${data.weight_difference} kg<br>
                        <strong>Timeframe:</strong> ${data.timeframe_days} days<br>
                        <strong>Difficulty:</strong> <span class="badge badge-${difficultyClass}">${data.difficulty.replace('_', ' ')}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Weekly Activity Target:</strong> ${data.weekly_minutes_target} minutes<br>
                        <strong>Daily Activity Target:</strong> ${data.daily_minutes_target} minutes<br>
                        <strong>Weekly Calorie Target:</strong> ${data.weekly_calorie_target} calories
                    </div>
                </div>
            `;
            document.getElementById('goalResults').style.display = 'block';
        } else {
            alert('Unable to calculate goal. Please ensure you have weight data logged.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while calculating your goal.');
    });
});
</script>

<style>
.progress {
    height: 20px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.alert {
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection