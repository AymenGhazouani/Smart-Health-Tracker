@extends('layouts.app') <!-- or your main layout -->

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
@endphp

<h1 class="text-2xl font-bold mb-6">Doctors - Admin</h1>

<a href="{{ route('doctor.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
    Add Doctor
</a>
<button id="addSpecialtyBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
    Add Specialty
</button>
<button id="viewSpecialtiesBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
    View Specialties
</button>

<!-- Add Specialty Modal -->
<div id="addSpecialtyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-bold mb-4">Add Specialty</h2>
        <form action="{{ route('specialties.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold">Name</label>
                <input type="text" name="name" class="border rounded w-full px-3 py-2" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeSpecialtyModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- View Specialties Modal -->
<div id="viewSpecialtiesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center overflow-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4">All Specialties</h2>
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @foreach($specialties as $specialty)
                <div class="flex justify-between items-center bg-gray-100 p-2 rounded">
                    <span class="specialty-name">{{ $specialty->name }}</span>
                    <div class="flex items-center space-x-2">
                        <!-- Edit Icon -->
                        <button type="button" class="edit-btn text-indigo-600 hover:text-indigo-800" 
                                data-id="{{ $specialty->id }}" data-name="{{ $specialty->name }}">
                            ✏️
                        </button>

                        <!-- Delete Icon -->
                        <form action="{{ route('specialties.destroy', $specialty->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">🗑️</button>
                        </form>
                    </div>
                </div>

                <!-- Hidden edit form -->
                <form action="" method="POST" class="edit-form mt-2 hidden" id="editForm{{ $specialty->id }}">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" class="border px-2 py-1 w-2/3" required>
                    <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded">Save</button>
                    <button type="button" class="cancel-edit px-2 py-1 bg-gray-300 rounded">Cancel</button>
                </form>
            @endforeach
        </div>
        <div class="flex justify-end mt-4">
            <button id="closeViewSpecialtiesModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    @foreach($doctors as $doctor)
        @php
            $color = $colors[$doctor->id % count($colors)];
        @endphp
        <div class="bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6 flex flex-col space-y-4">
            <!-- Doctor Info -->
            <div class="flex items-center space-x-4">
                <img src="{{ $doctor->profile_picture ?? 'https://apps.ump.edu.my/expertDirectory/img/staff2/profile_picture.jpg' }}" 
                     alt="{{ $doctor->name }}" 
                     class="w-16 h-16 rounded-full object-cover border-4 border-{{ $color }}-500 shadow-md">
                <div>
                    <h2 class="text-lg font-bold text-{{ $color }}-600">{{ $doctor->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $doctor->specialty->name ?? 'N/A' }}</p>
                    <p class="text-gray-600 text-xs">{{ $doctor->email }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-2 mt-4">
                <a href="{{ route('doctor.edit', $doctor->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Edit
                </a>
                <form action="{{ route('doctor.destroy', $doctor->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $doctors->links() }}
</div>

<script>
    // Add Specialty Modal
    const specialtyModal = document.getElementById('addSpecialtyModal');
    const specialtyBtn = document.getElementById('addSpecialtyBtn');
    const closeSpecialty = document.getElementById('closeSpecialtyModal');
    specialtyBtn.onclick = () => specialtyModal.classList.remove('hidden');
    closeSpecialty.onclick = () => specialtyModal.classList.add('hidden');

    // View Specialties Modal
    const viewSpecialtiesModal = document.getElementById('viewSpecialtiesModal');
    const viewSpecialtiesBtn = document.getElementById('viewSpecialtiesBtn');
    const closeViewSpecialties = document.getElementById('closeViewSpecialtiesModal');
    viewSpecialtiesBtn.onclick = () => viewSpecialtiesModal.classList.remove('hidden');
    closeViewSpecialties.onclick = () => viewSpecialtiesModal.classList.add('hidden');
</script>

@endsection
