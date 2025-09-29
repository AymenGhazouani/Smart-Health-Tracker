@extends('layouts.app')

@section('title', 'Admin · Edit Sleep for '.$user->name)

@section('content')
<form method="POST" action="{{ route('admin.metrics.sleep.update', [$user, $sleepSession]) }}">
    @csrf
    @method('PUT')
    <label>
        Started at
        <input type="datetime-local" name="started_at" value="{{ old('started_at', $sleepSession->started_at->format('Y-m-d\TH:i')) }}" required>
    </label>
    <label>
        Ended at
        <input type="datetime-local" name="ended_at" value="{{ old('ended_at', $sleepSession->ended_at->format('Y-m-d\TH:i')) }}" required>
    </label>
    <label>
        Quality (1-10)
        <input type="number" name="quality" min="1" max="10" value="{{ old('quality', $sleepSession->quality) }}">
    </label>
    <label>
        Note
        <input type="text" name="note" value="{{ old('note', $sleepSession->note) }}">
    </label>
    <div class="grid">
        <a role="button" href="{{ route('admin.metrics.sleep.index', $user) }}" class="secondary">Cancel</a>
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


