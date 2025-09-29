@extends('layouts.app')

@section('title', 'Admin · Edit Activity for '.$user->name)

@section('content')
<form method="POST" action="{{ route('admin.metrics.activities.update', [$user, $activity]) }}">
    @csrf
    @method('PUT')
    <label>
        Type
        <input type="text" name="type" value="{{ old('type', $activity->type) }}" required>
    </label>
    <label>
        Duration (min)
        <input type="number" name="duration_minutes" min="1" max="1440" value="{{ old('duration_minutes', $activity->duration_minutes) }}" required>
    </label>
    <label>
        Distance (km)
        <input type="number" step="0.01" name="distance_km" min="0" value="{{ old('distance_km', $activity->distance_km_times100 ? $activity->distance_km_times100/100 : null) }}">
    </label>
    <label>
        Calories
        <input type="number" name="calories" min="0" value="{{ old('calories', $activity->calories) }}">
    </label>
    <label>
        Performed at
        <input type="datetime-local" name="performed_at" value="{{ old('performed_at', $activity->performed_at->format('Y-m-d\TH:i')) }}" required>
    </label>
    <label>
        Note
        <input type="text" name="note" value="{{ old('note', $activity->note) }}">
    </label>
    <div class="grid">
        <a role="button" href="{{ route('admin.metrics.activities.index', $user) }}" class="secondary">Cancel</a>
        <button type="submit">Update</button>
    </div>

    @if ($errors->any())
        <article class="contrast">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </article>
    @endif
</form>
@endsection


