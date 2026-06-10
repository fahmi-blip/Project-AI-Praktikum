<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiagnoCare — @yield('title', 'Diagnosa Risiko Diabetes')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Serif+Display:ital@0;1&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/diagnocare.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <div class="nav-inner">
        <a href="{{ route('diagnosis.create') }}" class="nav-brand">
            <div class="nav-logo">
                <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <span class="nav-title">DiagnoCare</span>
        </a>
        {{-- <div class="nav-links">
            <a href="{{ route('diagnosis.create') }}"
               class="nav-link {{ request()->routeIs('diagnosis.create') ? 'active' : '' }}">
                Diagnosa
            </a>
        </div> --}}
        <span class="nav-badge">Fuzzy Mamdani</span>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</main>

<footer class="footer">
    DiagnoCare &mdash; Sistem Diagnosa Risiko Diabetes &mdash;
    Fuzzy Logic Mamdani &mdash; D4 Teknik Informatika UNAIR 2026
</footer>

@stack('scripts')
</body>
</html>