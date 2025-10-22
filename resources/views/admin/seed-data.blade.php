@extends('layouts.app')

@section('title', 'Seed Health Data')

@section('content')
<div class="metrics-dashboard">
    <header class="dashboard-header">
        <h1>🗄️ Health Data Management</h1>
        <p>Generate sample health data to test AI features</p>
    </header>

    <div class="metrics-grid">
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Current Data Stats -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h2 class="mb-0">{{ $stats['weights'] }}</h2>
                                    <p class="mb-0">Weight Records</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h2 class="mb-0">{{ $stats['activities'] }}</h2>
                                    <p class="mb-0">Activity Records</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h2 class="mb-0">{{ $stats['sleep_sessions'] }}</h2>
                                    <p class="mb-0">Sleep Records</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Management Actions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Generate Sample Data</h5>
                                </div>
                                <div class="card-body">
                                    <p>Generate realistic health data to test AI features:</p>
                                    <ul>
                                        <li><strong>90 days</strong> of weight measurements</li>
                                        <li><strong>60 days</strong> of activity records</li>
                                        <li><strong>45 days</strong> of sleep sessions</li>
                                    </ul>
                                    <p class="text-muted small">
                                        <i class="fas fa-info-circle"></i>
                                        This will create realistic patterns including weight trends, 
                                        activity variations, and sleep quality changes.
                                    </p>
                                    
                                    <form method="POST" action="{{ route('admin.seed-data.seed') }}" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-magic"></i>
                                            Generate Sample Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="fas fa-trash"></i> Clear All Data</h5>
                                </div>
                                <div class="card-body">
                                    <p>Remove all existing health data:</p>
                                    <ul>
                                        <li>All weight measurements</li>
                                        <li>All activity records</li>
                                        <li>All sleep sessions</li>
                                    </ul>
                                    <p class="text-warning small">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Warning:</strong> This action cannot be undone!
                                    </p>
                                    
                                    <form method="POST" action="{{ route('admin.seed-data.clear') }}" class="mt-3" 
                                          onsubmit="return confirm('Are you sure you want to delete all health data? This cannot be undone!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-trash-alt"></i>
                                            Clear All Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AI Features Links -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-robot"></i> Test AI Features</h5>
                                </div>
                                <div class="card-body">
                                    <p>After seeding data, explore these AI-powered features:</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="{{ route('health-ai.dashboard') }}" class="btn btn-primary btn-block mb-2">
                                                <i class="fas fa-brain"></i>
                                                AI Health Insights
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('health-predictions.dashboard') }}" class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-crystal-ball"></i>
                                                Health Predictions
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('health-ml.dashboard') }}" class="btn btn-success btn-block mb-2">
                                                <i class="fas fa-robot"></i>
                                                Machine Learning
                                            </a>
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

.card.bg-primary { background: var(--pico-primary) !important; color: var(--pico-primary-inverse); }
.card.bg-success { background: #10b981 !important; color: white; }
.card.bg-info { background: #3b82f6 !important; color: white; }

.card-header {
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
    padding: 1rem 1.5rem;
    margin: -2rem -2rem 1.5rem -2rem;
    border-radius: 1.5rem 1.5rem 0 0;
    font-weight: 600;
}

.card-header.bg-success {
    background: #10b981 !important;
}

.card-header.bg-danger {
    background: #ef4444 !important;
}

.card-header.bg-secondary {
    background: #6b7280 !important;
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

.btn-success {
    background: #10b981;
}

.btn-success:hover {
    background: #059669;
    color: white;
}

.btn-danger {
    background: #ef4444;
}

.btn-danger:hover {
    background: #dc2626;
    color: white;
}

.btn-primary {
    background: var(--pico-primary);
}

.btn-info {
    background: #3b82f6;
}

.btn-info:hover {
    background: #2563eb;
    color: white;
}

.btn-block {
    display: block;
    width: 100%;
}

.alert {
    padding: 1rem;
    border-radius: 0.75rem;
    margin-bottom: 1rem;
    border-left: 4px solid;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border-left-color: #10b981;
    color: #065f46;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border-left-color: #ef4444;
    color: #991b1b;
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
@endsection