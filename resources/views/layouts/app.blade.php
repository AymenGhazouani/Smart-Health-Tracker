<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Health Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>
<header class="container">
    <nav>
        <ul>
            <li><strong>Smart Health Tracker</strong></li>
        </ul>
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('weights.index') }}">Weights</a></li>
            <li><a href="{{ route('sleep-sessions.index') }}">Sleep</a></li>
            <li><a href="{{ route('activities.index') }}">Activities</a></li>
        </ul>
    </nav>
    <hr>
    <hgroup>
        <h2>@yield('title', 'Dashboard')</h2>
        <p>@yield('subtitle')</p>
    </hgroup>
    @if (session('status'))
        <article class="contrast">{{ session('status') }}</article>
    @endif
</header>

<main class="container">
    @yield('content')
</main>

<footer class="container">
    <hr>
    <small>&copy; {{ date('Y') }} Smart Health Tracker</small>
@yield('footer')
</footer>
</body>
</html>


