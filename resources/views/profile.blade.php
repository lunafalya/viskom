@extends('layouts.app')

@section('content')

<style>
.profile-page { padding: 10px; }

.profile-header-card,
.profile-box,
.security-box {
    background: var(--bg-card);
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 6px 20px rgba(0,0,0,.04);
    border: 1px solid var(--border-card);
}

.profile-top {
    display: flex;
    gap: 25px;
    align-items: center;
}

.avatar-wrap {
    position: relative;
    width: 110px;
    height: 110px;
    flex-shrink: 0;
}

.avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #f3f4f6;
}

.avatar-placeholder {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    font-weight: 700;
    color: var(--text-primary);
    border: 5px solid var(--border-color);
}

.avatar-upload-btn {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #2563eb;
    color: white;
    border: 2px solid white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
}

.badge-stat {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 14px;
    margin-right: 10px;
    margin-top: 12px;
    background: #dbeafe;
    color: #1d4ed8;
}

.profile-box { margin-top: 22px; }

.profile-box h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
    color: var(--text-heading);
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.form-group { margin-bottom: 16px; }

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    color: var(--text-primary);
}

input, select {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background: var(--bg-input);
    font-size: 15px;
    box-sizing: border-box;
    color: var(--text-primary);
}

.security-box {
    margin-top: 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-input);
}

.btn-save {
    padding: 14px 32px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 12px;
}

.btn-save:hover { background: #1d4ed8; }

.btn-outline {
    padding: 14px 26px;
    border: 2px solid #1d4ed8;
    color: #1d4ed8;
    background: white;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
}

.footer-note {
    margin-top: 35px;
    text-align: center;
    color: var(--text-secondary);
    font-size: 14px;
}

.status-msg {
    padding: 10px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
    background: #dcfce7;
    color: #15803d;
}

@media(max-width:1000px){
    .profile-top { flex-direction: column; text-align: center; }
    .form-row { grid-template-columns: 1fr; }
    .security-box { flex-direction: column; gap: 20px; align-items: flex-start; }
}
</style>

<div class="profile-page">

    @if(session('status') === 'profile-updated')
        <div class="status-msg">Profile updated successfully.</div>
    @endif
    @if(session('status') === 'picture-updated')
        <div class="status-msg">Profile picture updated.</div>
    @endif

    @if ($errors->any())
        <div style="color:#dc2626;font-size:14px;margin-bottom:16px;padding:10px 16px;background:#fee2e2;border-radius:8px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- HEADER --}}
    <div class="profile-header-card">
        <div class="profile-top">

            <div class="avatar-wrap">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="avatar">
                @else
                    <div class="avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif

                <form method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data" id="avatar-form">
                    @csrf
                    <input type="file" name="profile_picture" id="avatar-input" accept="image/*" style="display:none;">
                    <button type="button" class="avatar-upload-btn" onclick="document.getElementById('avatar-input').click();">+</button>
                </form>
            </div>

            <div>
                <h2 style="font-size:36px;margin-bottom:8px;">{{ $user->name }}</h2>
                <p style="font-size:16px;color:#6b7280;">
                    {{ $user->email }}
                </p>

                <span class="badge-stat">{{ $totalDrives }} Drives</span>
                <span class="badge-stat">{{ $totalHours }}h Monitored</span>
                <span class="badge-stat">{{ $safeRate }}% Safe</span>
            </div>

        </div>
    </div>


    {{-- PERSONAL DETAILS --}}
    <div class="profile-box">
        <h3>Personal Details</h3>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}">
            </div>

            <button type="submit" class="btn-save">Save Changes</button>
        </form>
    </div>


    {{-- SECURITY --}}
    <div class="security-box">
        <div>
            <h3 style="margin-bottom:5px;">Account Security</h3>
            <p style="color:#2563eb;">
                Your profile data is encrypted with enterprise-grade security.
            </p>
        </div>
    </div>

    <div class="footer-note">
        2024-2026 SafeDrive AI Vigilance Systems.
    </div>

</div>

<script>
    // Auto-submit avatar form when file selected
    document.getElementById('avatar-input').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('avatar-form').submit();
        }
    });
</script>

@endsection