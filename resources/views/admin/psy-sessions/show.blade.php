@extends('layouts.admin')

@section('page-title', 'Session Details')

@section('admin-content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Session #{{ $session->id }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Session Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.psy-sessions.edit', $session->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Edit Session
                </a>
                <a href="{{ route('admin.psy-sessions.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Back to Sessions
                </a>
            </div>
        </div>

        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Session ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">#{{ $session->id }}</dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Patient</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="font-medium">{{ $session->patient->name ?? 'Unknown' }}</div>
                        <div class="text-gray-500">{{ $session->patient->email ?? '' }}</div>
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Psychologist</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="font-medium">{{ $session->psychologist->name }}</div>
                        <div class="text-gray-500">{{ $session->psychologist->specialty }}</div>
                        <div class="text-gray-500">{{ $session->psychologist->email }}</div>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="font-medium">{{ $session->start_time->format('l, F j, Y') }}</div>
                        <div class="text-gray-500">{{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }}</div>
                        <div class="text-gray-500">Duration: {{ $session->duration }} minutes</div>
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($session->status === 'completed') bg-green-100 text-green-800
                            @elseif($session->status === 'cancelled') bg-red-100 text-red-800
                            @elseif($session->status === 'booked') bg-blue-100 text-blue-800
                            @elseif($session->status === 'confirmed') bg-yellow-100 text-yellow-800
                            @elseif($session->status === 'in_progress') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($session->status) }}
                        </span>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Session Fee</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($session->session_fee)
                            ${{ number_format($session->session_fee, 2) }}
                        @else
                            <span class="text-gray-400">Not set</span>
                        @endif
                    </dd>
                </div>
                
                @if($session->notes)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $session->notes }}</dd>
                </div>
                @endif
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $session->created_at->format('M d, Y \a\t g:i A') }}</dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $session->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Session Actions -->
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Session Actions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage this session</p>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="flex flex-wrap gap-4">
                @if($session->status === 'booked' || $session->status === 'confirmed')
                    <button onclick="cancelSession({{ $session->id }}, '{{ $session->patient->name }}')" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Session
                    </button>
                    
                    <button onclick="rescheduleSession({{ $session->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Reschedule
                    </button>
                @endif
                
                @if($session->status === 'booked' || $session->status === 'confirmed')
                    <button onclick="startSession({{ $session->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Start Session
                    </button>
                @endif
                
                @if($session->status === 'in_progress')
                    <button onclick="completeSession({{ $session->id }})" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Complete Session
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Session Notes -->
    @if($session->sessionNotes->count() > 0)
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Session Notes</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Private notes from the psychologist</p>
            </div>
            <a href="{{ route('admin.psy-sessions.notes.create', $session->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Note
            </a>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="space-y-4">
                @foreach($session->sessionNotes as $note)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $note->note_type)) }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $note->created_at->format('M d, Y g:i A') }}</span>
                                </div>
                                <div class="mt-2 text-sm text-gray-900">
                                    {{ $note->getDecryptedContent() }}
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <a href="{{ route('admin.psy-sessions.notes.edit', [$session->id, $note->id]) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button onclick="deleteNote({{ $note->id }})" 
                                        class="text-red-600 hover:text-red-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Session Notes</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">No notes have been added to this session yet</p>
        </div>
        
        <div class="px-4 py-5 sm:p-6 text-center">
            <a href="{{ route('admin.psy-sessions.notes.create', $session->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add First Note
            </a>
        </div>
    </div>
    @endif
</div>

<script>
function cancelSession(id, patientName) {
    const reason = prompt(`Enter cancellation reason for ${patientName}:`);
    if (reason !== null) {
        fetch(`/admin/psy-sessions/${id}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to cancel session: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error cancelling session');
        });
    }
}

function rescheduleSession(id) {
    const newTime = prompt('Enter new start time (YYYY-MM-DD HH:MM):');
    if (newTime !== null) {
        fetch(`/admin/psy-sessions/${id}/reschedule`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ new_start_time: newTime })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to reschedule session: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error rescheduling session');
        });
    }
}

function startSession(id) {
    fetch(`/admin/psy-sessions/${id}/start`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to start session: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error starting session');
    });
}

function completeSession(id) {
    const notes = prompt('Enter completion notes (optional):');
    if (notes !== null) {
        fetch(`/admin/psy-sessions/${id}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to complete session: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error completing session');
        });
    }
}

function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
        fetch(`/admin/psy-sessions/{{ $session->id }}/notes/${noteId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete note: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting note');
        });
    }
}
</script>
@endsection
