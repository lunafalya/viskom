@extends('layouts.app')

@section('content')
<div class="settings-page">

    {{-- PAGE HEADER --}}
    <div class="settings-header">
        <h1>System Settings</h1>
        <p>Configure your safety monitoring preferences and profile information.</p>
    </div>

    <div class="settings-grid">

        {{-- LEFT PROFILE CARD --}}
        <div class="settings-profile-card">

            <div class="profile-avatar">
                <img src="{{ asset('img/edit.jpg') }}" alt="Profile">
                <span class="edit-avatar">✏️</span>
            </div>

            <h3>Alex Thompson</h3>
            <p class="profile-id">Professional Driver ID: #SD-9921</p>

            <div class="profile-links">
                <a href="#">👤 Profile Information</a>
                <a href="#">🔒 Login & Security</a>
            </div>

            <button class="btn-logout">
                ⎋ Logout Account
            </button>
        </div>

        {{-- RIGHT SETTINGS --}}
        <div class="settings-sections">

            {{-- ALERT PREFERENCES --}}
            <div class="settings-card">
                <h2>🔔 Alert Preferences</h2>

                <div class="setting-item">
                    <div>
                        <strong>Audible Drowsiness Alert</strong>
                        <p>Play a sound when micro-sleep is detected</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div>
                        <strong>Visual Warnings</strong>
                        <p>Full-screen flash for critical safety risks</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div>
                        <strong>Emergency Contact Notify</strong>
                        <p>SMS alert after 3 critical events</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            {{-- PERMISSIONS --}}
            <div class="settings-card">
                <h2>🔐 Permissions</h2>

                <div class="permission-item success">
                    <div>
                        <strong>Camera Access</strong>
                        <p>Required for real-time monitoring</p>
                    </div>
                    <span class="badge success">Authorized</span>
                </div>

                <div class="permission-item info">
                    <div>
                        <strong>Location Services</strong>
                        <p>Used for trip mapping and speed tracking</p>
                    </div>
                    <a href="#" class="link">Manage</a>
                </div>
            </div>

            {{-- APPEARANCE --}}
            <div class="settings-card">
                <h2>🎨 Appearance</h2>

                <div class="setting-item">
                    <div>
                        <strong>Night Driving Mode</strong>
                        <p>Reduce screen glare during night shifts</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="settings-footer">
        SafeDrive AI Vigilance Engine v2.4.8-stable<br>
        © 2024 SafeDrive Safety Systems. All rights reserved.
    </footer>

</div>
@endsection