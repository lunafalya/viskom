<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SafeDrive AI</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
</head>

<body class="{{ auth()->user()?->settings?->dark_mode ? 'dark-mode' : '' }}">

<div class="dashboard">

    {{-- SIDEBAR OVERLAY (mobile) --}}
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>SafeDrive AI</h2>
            <p class="sidebar-sub">Vigilance System Active</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('history') }}" class="{{ request()->is('history') ? 'active' : '' }}">
                History
            </a>

            <a href="{{ route('settings') }}" class="{{ request()->is('settings') ? 'active' : '' }}">
                Settings
            </a>
        </nav>

        <a href="{{ route('monitor') }}" class="btn-primary sidebar-btn">
            Start Monitoring
        </a>
    </aside>

    {{-- MAIN --}}
    <div class="main">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="hamburger-btn" id="hamburger-btn" title="Toggle sidebar">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="topbar-title">Dashboard Overview</h1>
            </div>

            <div class="topbar-right">
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="topbar-logout-btn" title="Logout">Logout</button>
                </form>
                <a href="{{ route('profile') }}">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile" class="profile-img">
                    @else
                        <div class="profile-img" style="display:flex;align-items:center;justify-content:center;background:var(--border-color);color:var(--text-primary);font-weight:700;font-size:16px;">
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

<script>
(function() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebar-overlay');
    var btn = document.getElementById('hamburger-btn');
    var STORAGE_KEY = 'viskom_sidebar_collapsed';

    // Restore state from localStorage
    var collapsed = localStorage.getItem(STORAGE_KEY) === 'true';
    if (collapsed) sidebar.classList.add('collapsed');

    btn.addEventListener('click', function() {
        // On mobile (<=900px), toggle open class
        if (window.innerWidth <= 900) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        } else {
            // On desktop, toggle collapsed class
            sidebar.classList.toggle('collapsed');
            localStorage.setItem(STORAGE_KEY, sidebar.classList.contains('collapsed'));
        }
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    });
})();
</script>

</body>
</html>
