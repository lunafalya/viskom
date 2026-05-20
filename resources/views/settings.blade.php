@extends('layouts.app')

@section('content')
<style>
.settings-page { padding: 10px; }
.settings-header { margin-bottom: 24px; }
.settings-header h1 { font-size: 24px; font-weight: 700; color: var(--text-heading); margin: 0 0 4px 0; }
.settings-header p { color: var(--text-secondary); font-size: 14px; margin: 0; }
.settings-grid { display: grid; grid-template-columns: 280px 1fr; gap: 24px; }
.settings-profile-card { background: var(--bg-card); border-radius: 18px; padding: 28px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid var(--border-card); text-align: center; }
.settings-profile-card .profile-avatar { width: 80px; height: 80px; border-radius: 50%; background: var(--border-color); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; overflow: hidden; }
.settings-profile-card .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.settings-profile-card h3 { font-size: 18px; margin: 0 0 4px 0; color: var(--text-heading); }
.settings-profile-card .profile-id { font-size: 13px; color: var(--text-secondary); margin: 0 0 20px 0; }
.settings-profile-card .profile-links { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
.settings-profile-card .profile-links a { display: block; padding: 10px 14px; border-radius: 10px; color: var(--text-primary); text-decoration: none; font-size: 14px; transition: background 0.2s; text-align: left; }
.settings-profile-card .profile-links a:hover { background: var(--bg-input); }
.btn-logout { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #dc2626; background: transparent; color: #dc2626; font-size: 14px; font-weight: 500; cursor: pointer; }
.btn-logout:hover { background: #fef2f2; }
.settings-sections { display: flex; flex-direction: column; gap: 20px; }
.settings-card { background: var(--bg-card); border-radius: 18px; padding: 24px 28px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid var(--border-card); }
.settings-card h2 { font-size: 17px; font-weight: 600; color: var(--text-heading); margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 1px solid var(--border-color); }
.setting-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--border-color); }
.setting-item:last-child { border-bottom: none; }
.setting-item div strong { font-size: 14px; color: var(--text-heading); display: block; }
.setting-item div p { font-size: 13px; color: var(--text-secondary); margin: 2px 0 0 0; }
.switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #d1d5db; border-radius: 24px; transition: 0.3s; }
.slider:before { content: ''; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: 0.3s; }
input:checked + .slider { background: #2563eb; }
input:checked + .slider:before { transform: translateX(20px); }
.permission-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; }
.permission-item div strong { font-size: 14px; color: var(--text-heading); }
.permission-item div p { font-size: 13px; color: var(--text-secondary); margin: 2px 0 0 0; }
.badge-perm { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #15803d; }
.settings-footer { text-align: center; color: var(--text-secondary); font-size: 13px; margin-top: 32px; line-height: 1.6; }
@media (max-width: 900px) { .settings-grid { grid-template-columns: 1fr; } }
</style>

<div class="settings-page">

    <div class="settings-header">
        <h1>System Settings</h1>
        <p>Configure your safety monitoring preferences.</p>
    </div>

    <div class="settings-grid">

        {{-- LEFT PROFILE CARD --}}
        <div class="settings-profile-card">
            <div class="profile-avatar">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile">
                @else
                    <span style="font-size:28px;font-weight:700;color:#374151;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
            </div>

            <h3>{{ auth()->user()->name }}</h3>
            <p class="profile-id">{{ auth()->user()->email }}</p>

            <div class="profile-links">
                <a href="{{ route('profile') }}">Profile Information</a>
                <a href="{{ route('settings') }}">Login & Security</a>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout Account</button>
            </form>
        </div>

        {{-- RIGHT SETTINGS --}}
        <div class="settings-sections">

            {{-- ALERT PREFERENCES --}}
            <div class="settings-card">
                <h2>Alert Preferences</h2>

                <div class="setting-item">
                    <div>
                        <strong>Audible Drowsiness Alert</strong>
                        <p>Play graduated alarm tones when fatigue is detected</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="alarm_enabled" {{ $settings->alarm_enabled ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div>
                        <strong>Visual Warnings</strong>
                        <p>Full-screen red flash for critical safety events</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="visual_warning" {{ $settings->visual_warning ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div>
                        <strong>Developer Mode</strong>
                        <p>Show bounding boxes and debug overlay on monitor</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="dev_mode" {{ $settings->dev_mode ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            {{-- PERMISSIONS --}}
            <div class="settings-card">
                <h2>Permissions</h2>

                <div class="permission-item">
                    <div>
                        <strong>Camera Access</strong>
                        <p>Required for real-time monitoring</p>
                    </div>
                    <span class="badge-perm">Authorized</span>
                </div>
            </div>

            {{-- APPEARANCE --}}
            <div class="settings-card">
                <h2>Appearance</h2>

                <div class="setting-item">
                    <div>
                        <strong>Dark Mode</strong>
                        <p>Switch to a dark color scheme</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="dark_mode" {{ $settings->dark_mode ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

        </div>
    </div>

    <footer class="settings-footer">
        SafeDrive AI Vigilance Engine v3.0<br>
        2024-2026 SafeDrive Safety Systems.
    </footer>

</div>

<script>
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Save settings on any toggle change
    document.querySelectorAll('.settings-card input[type="checkbox"]').forEach(function(input) {
        input.addEventListener('change', function() {
            // Instant dark mode toggle -- no refresh needed
            if (this.id === 'dark_mode') {
                document.body.classList.toggle('dark-mode', this.checked);
            }

            fetch('{{ route("settings.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    alarm_enabled: document.getElementById('alarm_enabled').checked,
                    visual_warning: document.getElementById('visual_warning').checked,
                    dev_mode: document.getElementById('dev_mode').checked,
                    dark_mode: document.getElementById('dark_mode').checked,
                }),
            }).then(function(r) {
                if (r.ok) console.log('[settings] Saved');
            });
        });
    });
</script>

@endsection