@extends('layouts.app')

@section('content')

@php
    $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'indigo', 'pink'];
@endphp

<!-- Action Buttons Toolbar -->
<div class="bg-gray-50 p-4 rounded-lg shadow-md flex flex-wrap gap-3 mb-6 items-center justify-between">
    <!-- Left: Add Doctor -->
    <a href="{{ route('doctor.create') }}" 
       class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 transition transform hover:-translate-y-1">
        <span>➕</span>
        <span>Add Doctor</span>
    </a>

    <!-- Right: Specialty Buttons -->
    <div class="flex flex-wrap gap-3">
        <button id="addSpecialtyBtn" 
                class="flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-full shadow hover:bg-green-700 transition transform hover:-translate-y-1">
            <span>➕</span>
            <span>Add Specialty</span>
        </button>

        <button id="viewSpecialtiesBtn" 
                class="flex items-center gap-2 px-5 py-2 bg-red-600 text-white rounded-full shadow hover:bg-red-700 transition transform hover:-translate-y-1">
            <span>👁️</span>
            <span>View Specialties</span>
        </button>
    </div>
</div>


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
<!-- View Specialties Modal -->
<div id="viewSpecialtiesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center overflow-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4">All Specialties</h2>

        <div class="space-y-4 max-h-96 overflow-y-auto">
            @foreach($specialties as $specialty)
                <div class="flex flex-col bg-gray-100 p-2 rounded">
                    <div class="flex justify-between items-center">
                        <span class="specialty-name">{{ $specialty->name }}</span>

                        <div class="flex items-center space-x-2">
                            <!-- Edit button -->
                            <button type="button" class="edit-btn text-indigo-600 hover:text-indigo-800" data-id="{{ $specialty->id }}">
                                ✏️
                            </button>

                            <!-- Delete button -->
                            <form action="{{ route('specialties.destroy', $specialty->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">🗑️</button>
                            </form>
                        </div>
                    </div>

                    <!-- Hidden inline edit form -->
                    <form action="{{ route('specialties.update', $specialty->id) }}" method="POST" class="edit-form mt-2 hidden" id="editForm{{ $specialty->id }}">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $specialty->name }}" class="border px-2 py-1 w-full mb-2" required>
                        <div class="flex space-x-2">
                            <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded">Save</button>
                            <button type="button" class="cancel-edit px-2 py-1 bg-gray-300 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="flex justify-end mt-4">
            <button id="closeViewSpecialtiesModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>


<!-- Doctors Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
    @foreach($doctors as $doctor)
        @php $color = $colors[$doctor->id % count($colors)]; @endphp
        <div class="bg-white shadow-lg rounded-xl border-l-8 border-{{ $color }}-500 p-6 flex flex-col space-y-4 transition transform hover:scale-105 cursor-pointer"
             onclick="window.location='{{ route('doctor.showAdmin', $doctor->id) }}'">
            
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

    // Inline Edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            document.querySelectorAll('.edit-form').forEach(f => f.classList.add('hidden'));
            document.getElementById(`editForm${id}`).classList.remove('hidden');
        });
    });

    document.querySelectorAll('.cancel-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.edit-form').classList.add('hidden');
        });
    });
</script>

@endsection
