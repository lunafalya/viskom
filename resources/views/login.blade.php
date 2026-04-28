@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">

    <div class="auth-left">
        <div>
            <h1>SafeDrive AI</h1>
            <p>
                Intelligent driver monitoring for safer journeys.
                Experience real-time vigilance powered by AI.
            </p>
        </div>

        <div>
            <span class="auth-feature">🛡 Active Protection</span>
            <span class="auth-feature">📊 Real-time Analysis</span>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p>Please enter your credentials to access your dashboard.</p>

            <form>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" placeholder="name@company.com">
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label>Password</label>
                        <a href="#" style="color:#2563eb">Forgot password?</a>
                    </div>
                    <input type="password">
                </div>

                <div class="form-group">
    <a href="/dashboard" class="btn-primary" style="display:block;text-align:center">
        Login →
    </a>
</div>
            </form>

            <p style="margin-top:16px;font-size:.85rem;text-align:center">
                Don't have an account?
                <a href="#" style="color:#2563eb">Create a new account</a>
            </p>
        </div>
    </div>

</div>
@endsection