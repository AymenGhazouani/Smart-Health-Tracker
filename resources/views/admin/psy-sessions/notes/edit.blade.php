@extends('layouts.admin')

@section('page-title', 'Edit Session Note')

@section('admin-content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Note</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Update session note</p>
        </div>

        <!-- Session Info -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Session:</span>
                    <span class="text-gray-900">#{{ $session->id }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Patient:</span>
                    <span class="text-gray-900">{{ $session->patient->name ?? 'Unknown' }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Date:</span>
                    <span class="text-gray-900">{{ $session->start_time->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.psy-sessions.notes.update', [$session->id, $note->id]) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Note Type -->
                <div>
                    <label for="note_type" class="block text-sm font-medium text-gray-700">Note Type *</label>
                    <select name="note_type" id="note_type" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('note_type') border-red-300 @enderror">
                        <option value="">Select note type</option>
                        <option value="session_notes" {{ old('note_type', $note->note_type) == 'session_notes' ? 'selected' : '' }}>Session Notes</option>
                        <option value="assessment" {{ old('note_type', $note->note_type) == 'assessment' ? 'selected' : '' }}>Assessment</option>
                        <option value="follow_up" {{ old('note_type', $note->note_type) == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
                        <option value="treatment_plan" {{ old('note_type', $note->note_type) == 'treatment_plan' ? 'selected' : '' }}>Treatment Plan</option>
                        <option value="progress_notes" {{ old('note_type', $note->note_type) == 'progress_notes' ? 'selected' : '' }}>Progress Notes</option>
                        <option value="other" {{ old('note_type', $note->note_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('note_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Note Content *</label>
                    <textarea name="content" id="content" rows="8" required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('content') border-red-300 @enderror"
                              placeholder="Enter your notes here...">{{ old('content', $note->getDecryptedContent()) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">This note will be encrypted and only accessible to the psychologist who wrote it.</p>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Encryption -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_encrypted" id="is_encrypted" value="1" {{ old('is_encrypted', $note->is_encrypted) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_encrypted" class="ml-2 block text-sm text-gray-900">
                        Encrypt this note (recommended for sensitive information)
                    </label>
                </div>

                <!-- Note Info -->
                <div class="bg-gray-50 rounded-md p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Note Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-500">Created:</span>
                            <span class="text-gray-900">{{ $note->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Last Updated:</span>
                            <span class="text-gray-900">{{ $note->updated_at->format('M d, Y g:i A') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Type:</span>
                            <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $note->note_type)) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500">Encrypted:</span>
                            <span class="text-gray-900">{{ $note->is_encrypted ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.psy-sessions.show', $session->id) }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Note
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Note Guidelines -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">Note Guidelines</h3>
            <div class="mt-2 text-sm text-blue-700">
                <ul class="list-disc list-inside space-y-1">
                    <li>Notes are private and only accessible to the psychologist who wrote them</li>
                    <li>Encrypted notes provide additional security for sensitive information</li>
                    <li>Use appropriate note types to organize your documentation</li>
                    <li>Be professional and objective in your documentation</li>
                    <li>Include relevant observations, interventions, and patient responses</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
