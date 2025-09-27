@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
@endphp

<!-- Specialty Filter Pills -->
<div class="flex flex-wrap gap-2 mb-6">
    <!-- All -->
    <a href="{{ route('doctors.index') }}"
       class="px-4 py-2 rounded-full border {{ empty($specialtyId) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-500 hover:text-white transition">
        All
    </a>

    <!-- Each Specialty -->
    @foreach($specialties as $specialty)
        <a href="{{ route('doctors.index', ['specialty' => $specialty->id]) }}"
           class="px-4 py-2 rounded-full border {{ (isset($specialtyId) && $specialtyId == $specialty->id) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-500 hover:text-white transition">
            {{ $specialty->name }}
        </a>
    @endforeach
</div>

<!-- Doctors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($doctors as $index => $doctor)
        @php
            $color = $colors[$doctor->id % count($colors)];
        @endphp

        <div class="bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6 flex items-center space-x-6">
            <!-- Profile Picture -->
            <img 
                src="{{ $doctor->profile_picture ?? 'https://apps.ump.edu.my/expertDirectory/img/staff2/profile_picture.jpg' }}" 
                alt="{{ $doctor->name }}" 
                class="w-24 h-24 rounded-full object-cover border-4 border-{{ $color }}-500 shadow-md">

            <!-- Name + Specialty -->
            <div class="flex-1">
                <h2 class="text-xl font-bold text-{{ $color }}-600">{{ $doctor->name }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $doctor->specialty->name ?? 'N/A' }}</p>
            </div>

            <!-- View Details Button -->
            <a href="{{ route('doctors.show', $doctor->id) }}"
               class="bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white px-4 py-2 rounded-full transition">
                Details
            </a>
        </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $doctors->withQueryString()->links() }}
</div>

@endsection
