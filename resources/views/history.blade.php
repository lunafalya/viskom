@extends('layouts.app')

@section('content')
<div class="history-page">

    {{-- PAGE TITLE --}}
    <h1 class="page-title">Driving History</h1>

    {{-- STATS CARDS --}}
    <div class="stats-grid">
        <div class="stat-card blue">
            <span class="stat-label">TOTAL DRIVES</span>
            <h2>128</h2>
            <p class="stat-info green">▲ 12% from last month</p>
        </div>

        <div class="stat-card red">
            <span class="stat-label">TOTAL ALERTS</span>
            <h2>42</h2>
            <p class="stat-info red">▼ 5% decrease in fatigue</p>
        </div>

        <div class="stat-card green">
            <span class="stat-label">SAFE TIME</span>
            <h2>94.2%</h2>
            <p class="stat-info green">Peak safety performance</p>
        </div>

        <div class="stat-card blue">
            <span class="stat-label">HOURS MONITORED</span>
            <h2>312.5</h2>
            <p class="stat-info gray">Across all recorded sessions</p>
        </div>
    </div>

    {{-- RECENT SESSIONS --}}
    <div class="history-header">
        <h2>Recent Sessions</h2>

        <div class="history-actions">
            <button class="btn-secondary">Filter</button>
            <button class="btn-secondary">Export CSV</button>
        </div>
    </div>

    {{-- SESSION CARD --}}
    <div class="session-list">

        <div class="session-card danger">
            <div>
                <small>Oct 24, 2023 • <span class="badge danger">Night Commute</span></small>
                <h3>Late Night Highway Session</h3>
            </div>

            <div class="session-meta">
                <span>1h 45m</span>
                <span class="badge danger">4 Critical</span>
                <span class="badge gray">2 Minor</span>
            </div>

            <button class="btn-primary">▶ Watch History</button>
        </div>

        <div class="session-card success">
            <div>
                <small>Oct 23, 2023 • <span class="badge success">Morning Trip</span></small>
                <h3>Work Office Commute</h3>
            </div>

            <div class="session-meta">
                <span>35m</span>
                <span class="badge success">0 Alerts</span>
            </div>

            <button class="btn-primary">▶ Watch History</button>
        </div>

        <div class="session-card info">
            <div>
                <small>Oct 21, 2023 • <span class="badge info">Errands</span></small>
                <h3>City Drive Session</h3>
            </div>

            <div class="session-meta">
                <span>2h 10m</span>
                <span class="badge gray">1 Minor</span>
            </div>

            <button class="btn-primary">▶ Watch History</button>
        </div>

        <div class="session-card danger">
            <div>
                <small>Oct 20, 2023 • <span class="badge danger">Interstate Trip</span></small>
                <h3>Long Distance Monitoring</h3>
            </div>

            <div class="session-meta">
                <span>4h 30m</span>
                <span class="badge danger">2 Critical</span>
                <span class="badge gray">5 Minor</span>
            </div>

            <button class="btn-primary">▶ Watch History</button>
        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="pagination">
        <button>‹</button>
        <button class="active">1</button>
        <button>2</button>
        <button>3</button>
        <button>›</button>
    </div>

</div>
@endsection