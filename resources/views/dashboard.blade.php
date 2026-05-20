@extends('layouts.app')

@section('content')

<style>
.dash-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-top: 4px;
}

.dash-card {
    background: var(--bg-card);
    border-radius: 18px;
    padding: 36px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    border: 1px solid var(--border-card);
}

.dash-card-monitor {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 280px;
}

.dash-card-monitor .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.dash-card-monitor .status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #34d399;
    box-shadow: 0 0 8px #34d39966;
}

.dash-card-monitor h2 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-heading);
    margin: 0;
}

.dash-card-monitor p {
    color: #6b7280;
    font-size: 15px;
    margin: 8px 0 0 0;
    line-height: 1.6;
}

.dash-card-monitor .btn-start {
    display: inline-block;
    padding: 16px 40px;
    background: #2563eb;
    color: white;
    border-radius: 14px;
    font-size: 17px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: background 0.2s, transform 0.15s;
    margin-top: 24px;
    border: none;
    cursor: pointer;
}

.dash-card-monitor .btn-start:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

.dash-card-session {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.dash-card-session h3 {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1.5px;
    color: #6b7280;
    margin: 0 0 16px 0;
    text-transform: uppercase;
}

.dash-card-session .hours-value {
    font-size: 56px;
    font-weight: 700;
    color: var(--text-heading);
    line-height: 1;
}

.dash-card-session .hours-unit {
    font-size: 18px;
    color: #6b7280;
    font-weight: 500;
    margin-top: 8px;
}

.dash-card-session .hours-sub {
    font-size: 13px;
    color: #9ca3af;
    margin-top: 12px;
}

@media (max-width: 900px) {
    .dash-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="dash-grid">

    {{-- START DETECTION (large card) --}}
    <div class="dash-card dash-card-monitor">
        <div>
            <div class="card-header">
                <span class="status-dot"></span>
                <span style="color:#059669;font-size:14px;font-weight:500;">System Ready</span>
            </div>
            <h2>Driver Monitoring</h2>
            <p>
                Start real-time AI-powered vigilance detection.
                Ensure your face is fully visible in the camera frame for accurate eye and mouth tracking.
            </p>
        </div>
        <div>
            <a href="{{ route('monitor') }}" class="btn-start">
                Start Detection
            </a>
        </div>
    </div>

    {{-- ACTIVE SESSION (small card) --}}
    <div class="dash-card dash-card-session">
        <h3>Total Monitored</h3>
        <div class="hours-value">{{ $totalHours }}</div>
        <div class="hours-unit">hours</div>
        <div class="hours-sub">
            @if($totalHours > 0)
                Across all recorded sessions
            @else
                No sessions recorded yet
            @endif
        </div>
    </div>

</div>

@endsection
