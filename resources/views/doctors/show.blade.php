@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
    $color = $colors[$doctor->id % count($colors)];
@endphp

<div class="max-w-6xl mx-auto mt-8 flex flex-col lg:flex-row gap-8">

    <!-- Left: Picture + Basic Info -->
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

        <a href="{{ route('doctors.index') }}" 
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
                {{-- Placeholder description for now --}}
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur in leo nec justo efficitur facilisis.
            </p>
        </div>

        <!-- Reviews -->
<div class="bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-xl font-semibold mb-4">Reviews & Ratings</h2>

    @if($doctor->reviews->count())
        <div class="space-y-4 mb-6">
            @foreach($doctor->reviews as $review)
                <div class="border-b border-gray-200 pb-2">
                    <div class="flex justify-between items-center mb-1">
                        <p class="font-semibold">{{ $review->user->name ?? 'Anonymous' }}</p>
                        <p class="text-yellow-500">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $review->rating)
                                    ★
                                @else
                                    ☆
                                @endif
                            @endfor
                        </p>
                        {{-- Edit & Delete Icons --}}
        @if(auth()->check() && auth()->id() === $review->user_id)
            <div class="flex items-center space-x-3">
    <!-- Edit Button -->
    <a href="{{ route('reviews.edit', $review->id) }}" class="text-blue-500 hover:text-blue-700">
        ✎
    </a>

    <!-- Delete Button -->
    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">
            🗑
        </button>
    </form>
</div>

        @endif
                    </div>
                    <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 mb-6">No reviews yet.</p>
    @endif

    <!-- Add Review Form -->
    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

        <div x-data="{ rating: 0 }" class="space-y-2">
    <label class="block text-gray-700 font-semibold">Rating</label>

    <div class="flex space-x-1">
        @for($i = 1; $i <= 5; $i++)
            <button type="button"
                @click="rating = {{ $i }}"
                @mouseenter="rating = {{ $i }}"
                class="text-3xl focus:outline-none"
                :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'">
                ★
            </button>
        @endfor
    </div>

    <!-- Hidden input that actually gets submitted -->
    <input type="hidden" name="rating" :value="rating">
</div>


        <div>
            <label class="block text-gray-700 font-semibold">Comment</label>
            <textarea name="comment" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Write your review..."></textarea>
        </div>

        <button type="submit" class="bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white px-4 py-2 rounded-full transition">
            Submit Review
        </button>
    </form>
</div>

    </div>
</div>

@endsection
