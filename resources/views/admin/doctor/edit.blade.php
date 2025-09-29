@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
    $color = $colors[$doctor->id ?? rand(0,6) % count($colors)];
@endphp

<div class="max-w-3xl mx-auto mt-8 bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6">
    <h1 class="text-2xl font-bold mb-4">{{ isset($doctor) ? 'Edit Doctor' : 'Add Doctor' }}</h1>

    <form action="{{ isset($doctor) ? route('doctor.update', $doctor->id) : route('doctor.store') }}" method="POST">
        @csrf
        @if(isset($doctor))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name', $doctor->name ?? '') }}" class="w-full px-3 py-2 border rounded shadow-sm">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $doctor->email ?? '') }}" class="w-full px-3 py-2 border rounded shadow-sm">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Specialty</label>
            <select name="specialty_id" class="w-full px-3 py-2 border rounded shadow-sm">
                <option value="">Select specialty</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}" {{ (old('specialty_id', $doctor->specialty_id ?? '') == $specialty->id) ? 'selected' : '' }}>
                        {{ $specialty->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $doctor->phone ?? '') }}" class="w-full px-3 py-2 border rounded shadow-sm">
        </div>

        
        <button type="submit" class="px-4 py-2 bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white rounded transition">
            {{ isset($doctor) ? 'Update' : 'Create' }}
        </button>
    </form>
</div>

@endsection
