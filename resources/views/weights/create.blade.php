@extends('layouts.app')

@section('title', 'Add Weight')

@section('content')
<form method="POST" action="{{ route('weights.store') }}">
    @csrf
    <label>
        Value (kg)
        <input type="number" name="value_kg" step="0.01" min="1" max="500" value="{{ old('value_kg') }}" required>
    </label>
    <label>
        Measured at
        <input type="datetime-local" name="measured_at" value="{{ old('measured_at') }}" required>
    </label>
    <label>
        Note
        <input type="text" name="note" value="{{ old('note') }}">
    </label>
    <div class="grid">
        <a role="button" href="{{ route('weights.index') }}" class="secondary">Cancel</a>
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


