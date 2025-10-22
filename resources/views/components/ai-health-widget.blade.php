@php
    $healthAI = app(\App\Services\HealthAIService::class);
    $healthScore = $healthAI->calculateHealthScore(auth()->user());
    $recommendations = $healthAI->generateRecommendations(auth()->user());
    $alerts = $healthAI->detectAnomalies(auth()->user());
@endphp

<div class="metric-card ai-card">
    <div class="card-icon">🧠</div>
    <div class="card-content">
        <h3>AI Health Score</h3>
        <div class="metric-value ai-score-{{ strtolower($healthScore['status']) }}">
            {{ $healthScore['overall'] }}
        </div>
        <div class="metric-detail">
            {{ ucfirst($healthScore['status']) }} Health Status
        </div>
        
        @if(!empty($alerts))
            <div class="ai-alerts">
                <small class="alert-badge">{{ count($alerts) }} Alert{{ count($alerts) > 1 ? 's' : '' }}</small>
            </div>
        @endif
        
        @if(!empty($recommendations))
            <div class="ai-recommendations">
                <small class="recommendation-badge">{{ count($recommendations) }} Tip{{ count($recommendations) > 1 ? 's' : '' }}</small>
            </div>
        @endif
        
        <a href="{{ route('health-ai.dashboard') }}" class="card-action ai-action">
            View AI Insights
        </a>
        
        @if(($healthScore['overall'] ?? 0) == 0)
        <div class="mt-2">
            <a href="{{ route('admin.seed-data') }}" class="btn btn-sm btn-outline-secondary btn-block">
                <i class="fas fa-database"></i> Need Data? Generate Sample Data
            </a>
        </div>
        @endif
    </div>
</div>

<style>
.ai-card::before {
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
}

.ai-score-excellent {
    color: #10b981;
}

.ai-score-good {
    color: #3b82f6;
}

.ai-score-fair {
    color: #f59e0b;
}

.ai-score-needs_improvement {
    color: #ef4444;
}

.ai-alerts, .ai-recommendations {
    margin: 0.5rem 0;
    text-align: center;
}

.alert-badge {
    background: #fef2f2;
    color: #dc2626;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid #fecaca;
}

.recommendation-badge {
    background: #f0f9ff;
    color: #0369a1;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid #bae6fd;
}

.ai-action {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    border: 2px solid transparent;
}

.ai-action:hover {
    background: linear-gradient(135deg, #7c3aed, #db2777);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}
</style>