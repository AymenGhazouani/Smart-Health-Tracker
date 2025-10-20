@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
    $color = $colors[$doctor->id % count($colors)];
@endphp


<div class="max-w-6xl mx-auto mt-8 flex flex-col lg:flex-row gap-8 
            bg-gradient-to-r from-gray-100 via-white to-gray-100 p-6 rounded-xl shadow-lg">
    <!-- Left: Picture + Info -->
    <div class="lg:w-1/3 bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6 flex flex-col items-center space-y-4">
        <img src="{{ $doctor->profile_picture ?? 'https://apps.ump.edu.my/expertDirectory/img/staff2/profile_picture.jpg' }}" 
             alt="{{ $doctor->name }}" 
             class="w-40 h-40 rounded-full object-cover border-4 border-{{ $color }}-500 shadow-md">

        <h1 class="text-2xl font-bold text-{{ $color }}-600 text-center">{{ $doctor->name }}</h1>
        <p class="text-gray-500 text-lg">{{ $doctor->specialty->name ?? 'N/A' }}</p>

        <div class="mt-4 space-y-2 text-gray-600 w-full">
            <p><strong>Email:</strong> {{ $doctor->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $doctor->phone ?? 'N/A' }}</p>
        </div>

        <a href="{{ route('admin.doctor.index') }}" 
           class="mt-6 inline-block bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white px-4 py-2 rounded-full transition">
           Back to Doctors
        </a>
    </div>

    <!-- Right: Description + Reviews -->
    <div class="lg:w-2/3 flex flex-col space-y-6">
        <!-- Description -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">About {{ $doctor->name }}</h2>
            <p class="text-gray-700">
                {{ $doctor->description ?? 'No description provided.' }}
            </p>
        </div>

      <!-- Reviews -->
<div class="bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-xl font-semibold mb-4">Reviews & Ratings</h2>

    @if($doctor->reviews->count())
        <div class="space-y-4 mb-6">
            @foreach($doctor->reviews as $review)
                <div class="border-b border-gray-200 pb-2 flex justify-between items-start">
                    <div class="flex flex-col">
                        <p class="font-semibold">{{ $review->user->name ?? 'Anonymous' }}</p>

                        <!-- Rating stars -->
                        <p class="text-yellow-500 mb-1">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $review->rating) ★ @else ☆ @endif
                            @endfor
                        </p>

                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    </div>

                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-4 mt-1" onclick="return confirm('Delete this review?')">🗑️</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 mb-6">No reviews yet.</p>
    @endif
</div>

    </div>
</div>

@endsection
