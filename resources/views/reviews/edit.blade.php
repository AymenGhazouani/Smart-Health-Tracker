@extends('layouts.app')

@section('content')
@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
    $color = $colors[$review->doctor->id % count($colors)];
@endphp

<div class="max-w-md mx-auto mt-12">
    <div class="bg-white shadow-xl rounded-2xl border-l-8 border-{{ $color }}-500 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-{{ $color }}-600 text-white px-6 py-4">
            <h2 class="text-2xl font-bold text-center">Edit Your Review</h2>
        </div>

        <!-- Form -->
        <div class="p-6">
            <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Star Rating -->
                <div x-data="{ rating: {{ $review->rating }} }" class="space-y-2">
                    <label class="block text-gray-700 font-semibold">Rating</label>
                    <div class="flex space-x-2 text-3xl cursor-pointer">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating={{ $i }}" @mouseenter="rating={{ $i }}" 
                                    class="focus:outline-none" 
                                    :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>

                <!-- Comment -->
                <div>
                    <label class="block text-gray-700 font-semibold">Comment</label>
                    <textarea name="comment" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-{{ $color }}-400 focus:border-{{ $color }}-400"
                              placeholder="Write your comment...">{{ old('comment', $review->comment) }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white font-bold py-2 rounded-xl shadow-lg transition">
                    Update Review
                </button>
            </form>
        </div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
