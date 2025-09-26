@extends('layouts.app')

@section('title', 'Weights')

@section('content')
<div class="grid">
    <a role="button" href="{{ route('weights.create') }}">Add Weight</a>
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Value (kg)</th>
            <th>Note</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($weights as $w)
            <tr>
                <td>{{ $w->measured_at->format('Y-m-d H:i') }}</td>
                <td>{{ number_format((float)$w->value_kg, 2) }}</td>
                <td>{{ $w->note }}</td>
                <td class="grid">
                    <a role="button" href="{{ route('weights.edit', $w) }}">Edit</a>
                    <form method="POST" action="{{ route('weights.destroy', $w) }}" onsubmit="return confirm('Delete?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="contrast">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $weights->links() }}

@endsection


