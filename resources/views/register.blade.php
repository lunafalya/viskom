@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">

    {{-- LEFT SIDE --}}
    <div class="auth-left">

        <div>
            <h1>🛡 SafeDrive AI</h1>

            <p style="max-width:520px;">
                Experience the next generation of driver safety.
                Our AI-powered vigilance system ensures you stay
                focused on the road, providing real-time alerts
                when you need them most.
            </p>
        </div>

        <div style="margin-top:30px;display:flex;flex-direction:column;gap:18px;">

            <div class="auth-feature">
                📊 <div>
                    <strong>Real-time Analysis</strong><br>
                    <small>Instant facial tracking for fatigue detection.</small>
                </div>
            </div>

            <div class="auth-feature">
                🕒 <div>
                    <strong>Comprehensive History</strong><br>
                    <small>Track your safety metrics over every journey.</small>
                </div>
            </div>

        </div>

        <div style="margin-top:35px;">
            <img src="{{ asset('assets/img/dashboard-preview.jpg') }}"
                 style="width:100%;max-width:560px;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.15);">
        </div>

        <p style="margin-top:25px;font-size:.85rem;color:#6b7280;">
            © 2024 SafeDrive AI. Reliable. Vigilant. Professional.
        </p>

    </div>


    {{-- RIGHT SIDE --}}
    <div class="auth-right">

        <div class="auth-card">

            <h2>Create Account</h2>
            <p>Join the vigilance system today</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="driver@safedrive.ai">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••">
                </div>

                <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" style="width:auto;">
                    <small>
                        I agree to the
                        <a href="#" style="color:#2563eb">Terms of Service</a>
                        and
                        <a href="#" style="color:#2563eb">Privacy Policy</a>
                    </small>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;">
                    Register →
                </button>

            </form>

            <p style="margin-top:20px;text-align:center;font-size:.9rem;">
                Already have an account?
                <a href="{{ route('login') }}" style="color:#2563eb;font-weight:600;">
                    Log in
                </a>
            </p>

        </div>

    </div>

</div>
@endsection