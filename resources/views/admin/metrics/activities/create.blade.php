@extends('layouts.app')

@section('title', 'Admin · Add Activity for '.$user->name)

@section('content')
<form method="POST" action="{{ route('admin.metrics.activities.store', $user) }}">
    @csrf
    <label>
        Type
        <input type="text" name="type" value="{{ old('type') }}" required>
    </label>
    <label>
        Duration (min)
        <input type="number" name="duration_minutes" min="1" max="1440" value="{{ old('duration_minutes') }}" required>
    </label>
    <label>
        Distance (km)
        <input type="number" step="0.01" name="distance_km" min="0" value="{{ old('distance_km') }}">
    </label>
    <label>
        Calories
        <input type="number" name="calories" min="0" value="{{ old('calories') }}">
    </label>
    <label>
        Performed at
        <input type="datetime-local" name="performed_at" value="{{ old('performed_at') }}" required>
    </label>
    <label>
        Note
        <input type="text" name="note" value="{{ old('note') }}">
    </label>
    <div class="grid">
        <a role="button" href="{{ route('admin.metrics.activities.index', $user) }}" class="secondary">Cancel</a>
        <button type="submit">Save</button>
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


