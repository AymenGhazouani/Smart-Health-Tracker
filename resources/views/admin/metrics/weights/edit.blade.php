@extends('layouts.app')

@section('title', 'Admin · Edit Weight for '.$user->name)

@section('content')
<form method="POST" action="{{ route('admin.metrics.weights.update', [$user, $weight]) }}">
    @csrf
    @method('PUT')
    <label>
        Value (kg)
        <input type="number" name="value_kg" step="0.01" min="1" max="500" value="{{ old('value_kg', $weight->value_kg) }}" required>
    </label>
    <label>
        Measured at
        <input type="datetime-local" name="measured_at" value="{{ old('measured_at', $weight->measured_at->format('Y-m-d\TH:i')) }}" required>
    </label>
    <label>
        Note
        <input type="text" name="note" value="{{ old('note', $weight->note) }}">
    </label>
    <div class="grid">
        <a role="button" href="{{ route('admin.metrics.weights.index', $user) }}" class="secondary">Cancel</a>
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


