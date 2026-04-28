@extends('layouts.app')

@section('content')

<div class="hero">
    <small>TUTORIAL 01</small>
    <h1 style="margin-top:8px">Stay Awake, Stay Safe</h1>
    <p style="margin-top:8px;opacity:.9">
        Our AI monitors eye movement and posture to detect fatigue early.
    </p>
</div>

<div class="card-grid">
    <div class="card">
        <small style="color:green">● System Ready</small>
        <h3 style="margin-top:8px">Driver Monitoring</h3>
        <p style="margin-top:6px;color:#6b7280">
            Ensure your face is fully visible in the camera frame.
        </p>
        <button class="btn-primary" style="margin-top:12px">
            Start Detection
        </button>
    </div>

    <div class="card">
        <h3>Sensitivity</h3>
        <h2 style="margin-top:12px">High</h2>
        <small style="color:green">Optimized</small>
    </div>

    <div class="card">
        <h3>Active Session</h3>
        <h2 style="margin-top:12px">0.0h</h2>
        <small style="color:#9ca3af">No data</small>
    </div>
</div>

@endsection