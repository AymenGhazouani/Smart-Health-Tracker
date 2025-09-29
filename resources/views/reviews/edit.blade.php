@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-8 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Your Review</h2>

    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Rating</label>
            <select name="rating" class="block w-full border-gray-300 rounded mt-1">
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i>1?'s':'' }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-4">
            <label>Comment</label>
            <textarea name="comment" class="block w-full border-gray-300 rounded mt-1" rows="3">{{ $review->comment }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Review</button>
    </form>
</div>
@endsection
