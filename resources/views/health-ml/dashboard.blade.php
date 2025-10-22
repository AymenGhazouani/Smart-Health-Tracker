@extends('layouts.app')

@section('title', 'Machine Learning Health Analysis')

@section('content')
<div class="metrics-dashboard">
    <header class="dashboard-header">
        <h1>🤖 Machine Learning Health Analysis</h1>
        <p>Advanced neural networks and machine learning algorithms for comprehensive health insights</p>
    </header>

    <!-- Quick ML Explanation -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> What These ML Algorithms Do</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6><i class="fas fa-shield-alt text-danger"></i> Neural Network Risk Assessment</h6>
                    <p class="small">Analyzes your weight stability, activity levels, and sleep patterns to calculate a health risk score (0-100%). Higher scores suggest areas needing attention.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-chart-area text-primary"></i> ARIMA Weight Forecasting</h6>
                    <p class="small">Predicts your future weight by learning from your historical patterns. Shows what your weight might be in the next 10-30 days based on current trends.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-search text-warning"></i> Anomaly Detection</h6>
                    <p class="small">Automatically spots unusual changes in your health data (like sudden weight spikes or sleep disruptions) that might need your attention.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="metrics-grid">
                    
                    <!-- ML Risk Assessment -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Neural Network Risk Assessment</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($riskAssessment['status']) && $riskAssessment['status'] === 'insufficient_data')
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Insufficient data for ML risk assessment. Please log more health metrics.
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h2 class="text-{{ $riskAssessment['risk_level'] === 'low' ? 'success' : ($riskAssessment['risk_level'] === 'moderate' ? 'warning' : 'danger') }}">
                                                        {{ $riskAssessment['risk_score'] }}%
                                                    </h2>
                                                    <p class="mb-0">Risk Score</p>
                                                    <span class="badge badge-{{ $riskAssessment['risk_level'] === 'low' ? 'success' : ($riskAssessment['risk_level'] === 'moderate' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($riskAssessment['risk_level']) }} Risk
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4>{{ $riskAssessment['confidence'] }}%</h4>
                                                    <p class="mb-0">ML Confidence</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Health Pattern Classification:</h6>
                                                <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $riskAssessment['health_cluster'])) }}</span>
                                                
                                                <h6 class="mt-3">Features Analyzed:</h6>
                                                <div class="d-flex flex-wrap">
                                                    @foreach($riskAssessment['features_used'] as $feature)
                                                        <span class="badge badge-secondary mr-1 mb-1">{{ ucfirst(str_replace('_', ' ', $feature)) }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(!empty($riskAssessment['ml_recommendations']))
                                            <div class="mt-4">
                                                <h6>ML-Generated Recommendations:</h6>
                                                @foreach($riskAssessment['ml_recommendations'] as $rec)
                                                    <div class="alert alert-{{ $rec['priority'] === 'high' ? 'danger' : 'info' }}">
                                                        <strong>{{ ucfirst($rec['type']) }}:</strong> {{ $rec['message'] }}
                                                        <small class="float-right">Confidence: {{ round($rec['confidence'] * 100) }}%</small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ML Weight Forecasting -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> ARIMA Weight Forecasting</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($weightForecast['status']) && $weightForecast['status'] === 'insufficient_data')
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Need at least 10 weight measurements for ML forecasting.
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <strong>What ARIMA Predicts:</strong>
                                            <p class="small text-muted mb-2">
                                                ARIMA (AutoRegressive Integrated Moving Average) analyzes your historical weight patterns to predict future weight changes. 
                                                It considers trends, seasonality, and random fluctuations in your data.
                                            </p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Model Accuracy:</strong> {{ $weightForecast['model_accuracy'] * 100 }}%
                                        </div>
                                        
                                        <h6>Next 10 Days Forecast:</h6>
                                        <div class="forecast-container" style="max-height: 200px; overflow-y: auto;">
                                            @foreach(array_slice($weightForecast['forecast'], 0, 10) as $index => $prediction)
                                                <div class="d-flex justify-content-between border-bottom py-1">
                                                    <span>{{ \Carbon\Carbon::now()->addDays($index + 1)->format('M j') }}:</span>
                                                    <span class="font-weight-bold">{{ $prediction }} kg</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Predictions based on your weight history patterns and mathematical modeling
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Anomaly Detection -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="mb-0"><i class="fas fa-search"></i> Isolation Forest Anomaly Detection</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($anomalies['status']) && $anomalies['status'] === 'insufficient_data')
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Need at least 7 days of data for anomaly detection.
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <strong>Anomaly Score:</strong> 
                                            <span class="badge badge-{{ $anomalies['anomaly_score'] > 50 ? 'danger' : ($anomalies['anomaly_score'] > 25 ? 'warning' : 'success') }}">
                                                {{ $anomalies['anomaly_score'] }}%
                                            </span>
                                        </div>
                                        
                                        @if(!empty($anomalies['anomalies']))
                                            <h6>Detected Anomalies:</h6>
                                            @foreach($anomalies['anomalies'] as $anomaly)
                                                <div class="alert alert-danger py-2">
                                                    <small>
                                                        <strong>Data Point {{ $anomaly['index'] }}:</strong>
                                                        Isolation Score: {{ round($anomaly['score'], 3) }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle"></i>
                                                No significant anomalies detected in your health patterns.
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Uses Isolation Forest algorithm to detect unusual patterns
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ML Algorithms Explanation -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-brain"></i> Machine Learning Algorithms Used
                                        <button class="btn btn-sm btn-outline-light float-right" type="button" data-toggle="collapse" data-target="#mlExplanation">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </h5>
                                </div>
                                <div class="collapse" id="mlExplanation">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="ai-method-card">
                                                    <div class="method-icon">
                                                        <i class="fas fa-network-wired fa-2x text-danger"></i>
                                                    </div>
                                                    <h6>Neural Network</h6>
                                                    <p class="small text-muted">
                                                        <strong>What it does:</strong> Combines multiple health factors (weight stability, activity level, sleep quality) 
                                                        to calculate your overall health risk score (0-100%).
                                                        <br><br>
                                                        <strong>Example:</strong> If you have irregular weight changes + low activity + poor sleep, 
                                                        the network might output 75% risk score, suggesting health attention needed.
                                                    </p>
                                                    <div class="formula-box">
                                                        <code>σ(Wx + b)</code>
                                                        <small class="d-block">Combines your health metrics with learned weights to predict risk</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="ai-method-card">
                                                    <div class="method-icon">
                                                        <i class="fas fa-chart-area fa-2x text-primary"></i>
                                                    </div>
                                                    <h6>ARIMA Forecasting</h6>
                                                    <p class="small text-muted">
                                                        <strong>What it does:</strong> Predicts your future weight by analyzing patterns in your historical data. 
                                                        It looks at trends (are you gaining/losing?), seasonality (weekly patterns), and random variations.
                                                        <br><br>
                                                        <strong>Example:</strong> If you've been losing 0.2kg per week consistently, ARIMA will predict you'll continue this trend, 
                                                        accounting for your typical daily fluctuations.
                                                    </p>
                                                    <div class="formula-box">
                                                        <code>X(t) = φX(t-1) + ε</code>
                                                        <small class="d-block">AR(1) model: Next weight = (factor × current weight) + random variation</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="ai-method-card">
                                                    <div class="method-icon">
                                                        <i class="fas fa-search fa-2x text-warning"></i>
                                                    </div>
                                                    <h6>Isolation Forest</h6>
                                                    <p class="small text-muted">
                                                        <strong>What it does:</strong> Automatically detects unusual patterns in your health data that might indicate 
                                                        problems or significant changes in your health routine.
                                                        <br><br>
                                                        <strong>Example:</strong> If you suddenly gain 3kg in one week when you normally fluctuate ±0.5kg, 
                                                        or if your sleep quality drops dramatically, it flags these as anomalies.
                                                    </p>
                                                    <div class="formula-box">
                                                        <code>s(x) = 2^(-E(h(x))/c(n))</code>
                                                        <small class="d-block">Calculates how "isolated" or unusual each data point is</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="ai-method-card">
                                                    <div class="method-icon">
                                                        <i class="fas fa-project-diagram fa-2x text-success"></i>
                                                    </div>
                                                    <h6>K-Means Clustering</h6>
                                                    <p class="small text-muted">Groups health patterns into clusters to identify your health profile type and compare with similar patterns.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="ai-method-card">
                                                    <div class="method-icon">
                                                        <i class="fas fa-sitemap fa-2x text-info"></i>
                                                    </div>
                                                    <h6>Decision Tree</h6>
                                                    <p class="small text-muted">Rule-based classification system that generates personalized recommendations based on your health feature patterns.</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <h6><i class="fas fa-graduation-cap text-success"></i> Real Machine Learning Implementation</h6>
                                            <p class="mb-0 small">
                                                This system implements actual ML algorithms including neural networks, time series forecasting, 
                                                and unsupervised learning. All computations are performed locally using mathematical models 
                                                trained on health data patterns.
                                            </p>
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

.card-header.bg-danger {
    background: #ef4444 !important;
}

.card-header.bg-primary {
    background: var(--pico-primary) !important;
}

.card-header.bg-warning {
    background: #f59e0b !important;
}

.card-header.bg-dark {
    background: #374151 !important;
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
.badge-secondary { background: #6b7280; color: white; }

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

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border-left-color: #10b981;
    color: #065f46;
}

.text-success { color: #10b981 !important; }
.text-warning { color: #f59e0b !important; }
.text-danger { color: #ef4444 !important; }
.text-info { color: #3b82f6 !important; }

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
@endsection