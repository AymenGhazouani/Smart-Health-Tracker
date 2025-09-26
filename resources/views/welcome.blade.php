@extends('layouts.guest')

@section('content')
<div class="text-center">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to Smart Health Tracker</h1>
    <p class="text-gray-600 mb-8">Your comprehensive health management platform</p>
    
    <div class="space-y-4">
        <a href="{{ route('login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block">
            Sign In
        </a>
        <a href="{{ route('register') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out block">
            Create Account
        </a>
    </div>
</div>
@endsection