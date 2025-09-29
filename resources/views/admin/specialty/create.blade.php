@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto mt-8 bg-white shadow-lg rounded-xl p-6">
    <h1 class="text-2xl font-bold mb-4">Add Specialty</h1>

    <form action="{{ route('specialties.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded shadow-sm" required>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
            Create
        </button>
    </form>
</div>

@endsection
