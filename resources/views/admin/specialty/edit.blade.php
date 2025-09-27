@foreach($specialties as $specialty)
    <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
        <h2 class="text-lg font-bold text-gray-800">{{ $specialty->name }}</h2>

        <div class="flex space-x-2 mt-4">
            <!-- Edit button -->
            <a href="{{ route('specialties.edit', $specialty->id) }}" 
               class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
               Edit
            </a>

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
