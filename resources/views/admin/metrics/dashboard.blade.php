@extends('layouts.app')

@section('title', 'Admin · Health Metrics')

@section('content')
<div class="admin-metrics-container">
    @if(!$selectedUserId)
    <div class="user-selector-modal">
        <div class="user-selector-content">
            <h2>Select User to Manage</h2>
            <form method="GET" class="user-form">
                <label for="user_id">Choose a user to manage their health metrics</label>
                <select id="user_id" name="user_id" onchange="this.form.submit()" class="user-select">
                    <option value="">-- Choose a user --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ (int)$selectedUserId === $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @endif

    @if($selectedUserId)
    <div class="metrics-grid">
        <article class="metric-card">
            <header>Latest Weight</header>
            <h3>{{ $latestWeight? number_format((float)$latestWeight->value_kg, 2).' kg' : 'No data' }}</h3>
            @if($latestWeight)
                <p>Measured at: {{ $latestWeight->measured_at->format('Y-m-d H:i') }}</p>
            @endif
            <a role="button" href="{{ route('admin.metrics.weights.index', $selectedUserId) }}" class="manage-btn">Manage Weights</a>
        </article>

        <article class="metric-card">
            <header>Average Sleep (7 days)</header>
            <h3>{{ $avgSleepMins ? $avgSleepMins.' mins' : 'No data' }}</h3>
            <a role="button" href="{{ route('admin.metrics.sleep.index', $selectedUserId) }}" class="manage-btn">Manage Sleep</a>
        </article>

        <article class="metric-card">
            <header>Activity (7 days)</header>
            <h3>
                {{ optional($activityTotals)->minutes ?? 0 }} mins
                @if(optional($activityTotals)->calories)
                    · {{ $activityTotals->calories }} kcal
                @endif
            </h3>
            <a role="button" href="{{ route('admin.metrics.activities.index', $selectedUserId) }}" class="manage-btn">Manage Activities</a>
        </article>
    </div>
    @endif
</div>

<style>
.admin-metrics-container {
    min-height: 80vh;
    position: relative;
}

.user-selector-modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

/* Blur only the background via overlay */
.user-selector-modal::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
}

.user-selector-content {
    position: relative; /* keep above overlay */
    background: var(--pico-background-color);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    text-align: center;
    max-width: 500px;
    width: 90%;
}

.user-selector-content h2 {
    margin-bottom: 1.5rem;
    color: var(--pico-primary);
}

.user-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.user-select {
    padding: 0.75rem;
    font-size: 1.1rem;
    border: 2px solid var(--pico-primary);
    border-radius: 0.5rem;
    background: var(--pico-background-color);
    color: var(--pico-color);
}

.user-select:focus {
    outline: none;
    border-color: var(--pico-primary);
    box-shadow: 0 0 0 3px rgba(var(--pico-primary-rgb), 0.2);
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.metric-card {
    background: var(--pico-background-color);
    border: 2px solid var(--pico-muted-border-color);
    border-radius: 1rem;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: var(--pico-primary);
}

.metric-card header {
    color: var(--pico-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.metric-card h3 {
    font-size: 2rem;
    margin: 1rem 0;
    color: var(--pico-color);
}

.manage-btn {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    background: var(--pico-primary);
    color: var(--pico-primary-inverse);
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.manage-btn:hover {
    background: var(--pico-primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--pico-primary-rgb), 0.3);
}
</style>
@endsection


