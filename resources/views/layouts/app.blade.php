<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SafeDrive AI</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

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
        <button class="notif-btn">🔔</button>
        <a href="{{ route('profile') }}">
            <img src="{{ asset('img/edit.jpg') }}" alt="Profile" class="profile-img">
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