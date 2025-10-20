@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
    $color = $colors[$doctor->id ?? rand(0,6) % count($colors)];
@endphp

<h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">Add Doctor</h1>

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-8">
    @if ($errors->any())
    <div class="mb-4 p-4 border border-red-400 bg-red-100 text-red-700 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('doctors.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Name</label>
            <input type="text" name="name" placeholder="Dr. John Doe"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $color }}-400 focus:outline-none" required>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" placeholder="doctor@example.com"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $color }}-400 focus:outline-none" required>
        </div>

       <div>
    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
    <input type="text" name="phone" id="phone" placeholder="12345678"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $color }}-400 focus:outline-none"
           maxlength="8">
           
</div>

<script>
    const phoneInput = document.getElementById('phone');

    phoneInput.addEventListener('input', function() {
        // Remove any non-digit character
        this.value = this.value.replace(/\D/g, '');
        
        // Limit to 8 digits
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
</script>


        <div>
            <label class="block text-gray-700 font-semibold mb-2">Specialty</label>
            <select name="specialty_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $color }}-400 focus:outline-none" required>
                <option value="">Select a specialty</option>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Description</label>
            <textarea name="description" rows="4" placeholder="Write a short bio or description for the doctor..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-{{ $color }}-400 focus:outline-none"></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                Add Doctor
            </button>
        </div>
    </form>
</div>

@endsection
