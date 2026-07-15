<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Quiz Paesi del Mondo') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="topbar">
        <h1>🌍 Quiz Paesi del Mondo</h1>
        <a href="{{ route('home') }}">Torna alla home</a>
    </div>
    <div class="page">
        @yield('content')
    </div>
</body>
</html>
