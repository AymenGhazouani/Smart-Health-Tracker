@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Specialties</h1>

<a href="{{ route('specialties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
    Add Specialty
</a>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($specialties as $specialty)
    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
        <h2 class="text-lg font-bold text-gray-800">{{ $specialty->name }}</h2>

        <div class="flex space-x-2 mt-4">
            <a href="{{ route('specialties.edit', $specialty->id) }}" class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Edit
            </a>

            <form action="{{ route('specialties.destroy', $specialty->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $specialties->links() }}
</div>
@endsection
