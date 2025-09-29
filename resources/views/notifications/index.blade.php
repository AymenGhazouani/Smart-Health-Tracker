@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">Mark all as read</button>
        </form>
    </div>

    @if($notifications->count() === 0)
        <div class="bg-white shadow rounded-lg p-8 text-center text-gray-500">
            No notifications.
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-lg divide-y">
            @foreach($notifications as $n)
                <div class="p-4 flex items-start justify-between {{ is_null($n->read_at) ? 'bg-blue-50' : '' }}">
                    <div>
                        <div class="text-sm text-gray-900 font-semibold">{{ data_get($n->data, 'title', 'Notification') }}</div>
                        <div class="text-sm text-gray-700 mt-1">{{ data_get($n->data, 'message') }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="ml-4">
                        @if(is_null($n->read_at))
                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">Mark as read</button>
                        </form>
                        @else
                        <span class="text-gray-400 text-xs">Read</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection


