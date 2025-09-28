@foreach($specialties as $specialty)
    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
        <h2 class="text-lg font-bold text-gray-800">{{ $specialty->name }}</h2>

        <div class="flex space-x-2 mt-4">
            <!-- Edit button -->
           <form action="{{ route('specialties.update', $specialty->id) }}" method="POST" class="edit-form mt-2 hidden" id="editForm{{ $specialty->id }}">
    @csrf
    @method('PUT')
    <input type="text" name="name" value="{{ $specialty->name }}" class="border px-2 py-1 w-2/3" required>
    <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded">Save</button>
    <button type="button" class="cancel-edit px-2 py-1 bg-gray-300 rounded">Cancel</button>
</form>
<button type="button" class="edit-btn px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition" 
        data-id="{{ $specialty->id }}" data-name="{{ $specialty->name }}">
    Edit
</button>




            <!-- Delete button -->
            <form action="{{ route('specialties.destroy', $specialty->id) }}" method="POST" 
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endforeach
