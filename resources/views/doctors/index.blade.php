@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
@endphp
<!-- Banner Section -->
<div class="w-full h-64 bg-cover bg-center rounded-lg mb-6"
     style="background-image: url('https://img.freepik.com/premium-photo/health-light-blue-medical-background_87720-136356.jpg');">
    <div class="bg-black bg-opacity-30 w-full h-full flex items-center justify-center rounded-lg">
        <h1 class="text-white text-3xl md:text-5xl font-bold">Our Doctors</h1>
    </div>
</div>

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

       <a href="{{ route('doctors.show', $doctor->id) }}" class="block group">
    <div class="bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6 flex items-center space-x-6 relative overflow-hidden transform transition hover:scale-105 cursor-pointer">
        
        <!-- Accent shape in top-right corner -->
        <div class="absolute top-0 right-0 w-24 h-24 bg-{{ $color }}-100 rounded-full opacity-30 -translate-x-6 -translate-y-6"></div>

        <!-- Profile Picture -->
        <img 
            src="{{ $doctor->profile_picture ?? 'https://apps.ump.edu.my/expertDirectory/img/staff2/profile_picture.jpg' }}" 
            alt="{{ $doctor->name }}" 
            class="w-24 h-24 rounded-full object-cover border-4 border-{{ $color }}-500 shadow-md">

        <!-- Name + Specialty + Extra Info -->
        <div class="flex-1">
            <h2 class="text-xl font-bold text-{{ $color }}-600">{{ $doctor->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $doctor->specialty->name ?? 'N/A' }}</p>
            
            <!-- Extra info -->
            <div class="flex flex-col text-gray-500 text-sm mt-2 space-y-1">
                <p><span class="font-semibold">Email:</span> {{ $doctor->email ?? 'N/A' }}</p>
                <p><span class="font-semibold">Phone:</span> {{ $doctor->phone ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Hover overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center rounded-xl opacity-0 group-hover:opacity-100 transition">
            <span class="text-white font-semibold text-lg">View Details</span>
        </div>

    </div>
</a>

    @endforeach
</div>


<!-- Pagination -->
<div class="mt-6">
    {{ $doctors->withQueryString()->links() }}
</div>

@endsection
