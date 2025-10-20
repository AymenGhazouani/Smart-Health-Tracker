@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
@endphp

<!-- Banner Section -->
<div class="w-full h-64 bg-cover bg-center rounded-lg mb-6 relative"
     style="background-image: url('https://img.freepik.com/premium-photo/health-light-blue-medical-background_87720-136356.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center rounded-lg">
        <h1 class="text-white text-3xl md:text-5xl font-extrabold drop-shadow-lg">Our Doctors</h1>
    </div>
</div>

<!-- Search & Filters -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
    <!-- Specialty Filter Pills -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('doctors.index') }}"
           class="px-4 py-2 rounded-full border {{ empty($specialtyId) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-500 hover:text-white transition">
            All
        </a>
        @foreach($specialties as $specialty)
            <a href="{{ route('doctors.index', ['specialty' => $specialty->id]) }}"
               class="px-4 py-2 rounded-full border {{ (isset($specialtyId) && $specialtyId == $specialty->id) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' }} hover:bg-blue-500 hover:text-white transition">
                {{ $specialty->name }}
            </a>
        @endforeach
    </div>

    <!-- Search & PDF -->
    <div class="flex gap-2 w-full md:w-auto">
        <form action="{{ route('doctors.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="search" placeholder="Search by name or email" 
                   value="{{ $search ?? '' }}" 
                   class="border p-2 rounded w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="bg-blue-500 text-white px-4 rounded hover:bg-blue-600 transition">Search</button>
        </form>
        <a href="{{ route('doctors.pdf') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition whitespace-nowrap">
            Download PDF
        </a>
    </div>
</div>


<!-- Doctors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    @foreach($doctors as $doctor)
        @php
            $color = $colors[$doctor->id % count($colors)];
        @endphp
        

        <a href="{{ route('doctors.show', $doctor->id) }}" class="group">
            <div class="bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 transform transition hover:scale-105 cursor-pointer relative overflow-hidden">
                
                <!-- Accent shape -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-{{ $color }}-100 rounded-full opacity-20 -translate-x-6 -translate-y-6"></div>

                <!-- Profile -->
                <img src="{{ $doctor->profile_picture ?? 'https://apps.ump.edu.my/expertDirectory/img/staff2/profile_picture.jpg' }}" 
                     alt="{{ $doctor->name }}" 
                     class="w-28 h-28 rounded-full object-cover border-4 border-{{ $color }}-500 shadow-md">

                <!-- Info -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-xl font-bold text-{{ $color }}-600">{{ $doctor->name }}</h2>
                    <span class="inline-block mt-1 px-2 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 text-sm font-medium">{{ $doctor->specialty->name ?? 'N/A' }}</span>
                    
                    <div class="mt-2 text-gray-600 text-sm space-y-1">
                        <p><span class="font-semibold">Email:</span> {{ $doctor->email ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Phone:</span> {{ $doctor->phone ?? 'N/A' }}</p>
                        <p><span class="font-semibold">Description:</span> {{ $doctor->description ?? 'N/A' }}</p>
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
