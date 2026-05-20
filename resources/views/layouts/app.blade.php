<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SafeDrive AI</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="{{ auth()->user()?->settings?->dark_mode ? 'dark-mode' : '' }}">

<div class="dashboard">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <h2>SafeDrive AI</h2>
        <p class="sidebar-sub">Vigilance System Active</p>

        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('history') }}" class="{{ request()->is('history') ? 'active' : '' }}">
            History
        </a>

        <a href="{{ route('settings') }}" class="{{ request()->is('settings') ? 'active' : '' }}">
            Settings
        </a>

        <div class="form-group">

        </div>
        <a href="{{ route('monitor') }}" class="btn-primary sidebar-btn">
            Start Monitoring
        </a>
    </aside>

    {{-- MAIN --}}
    <div class="main">

{{-- TOPBAR --}}
<header class="topbar">
    <h1 class="topbar-title">Dashboard Overview</h1>

    <div class="topbar-right">
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="notif-btn" title="Logout" style="cursor:pointer;">Logout</button>
        </form>
        <a href="{{ route('profile') }}">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile" class="profile-img">
            @else
                <div class="profile-img" style="display:flex;align-items:center;justify-content:center;background:#e5e7eb;color:#374151;font-weight:700;font-size:16px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
        </a>
    </div>
</header>

        {{-- CONTENT --}}
        <main class="content">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
