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

        <a href="/dashboard" class="active">Dashboard</a>
        <a href="/history">History</a>
        <a href="/settings">Settings</a>

        <button class="btn-primary sidebar-btn">
            Start Monitoring
        </button>
    </aside>

    {{-- MAIN --}}
    <div class="main">

{{-- TOPBAR --}}
<header class="topbar">
    <h1 class="topbar-title">Dashboard Overview</h1>

    <div class="topbar-right">
        <button class="notif-btn">🔔</button>
        <img src="{{ asset('img/edit.jpg') }}" alt="Profile" class="profile-img">
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