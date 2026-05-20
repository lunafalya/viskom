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
            <span class="auth-feature">Active Protection</span>
            <span class="auth-feature">Real-time Analysis</span>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p>Please enter your credentials to access your dashboard.</p>

            @if ($errors->any())
            <div style="color:#dc2626;font-size:14px;margin-bottom:12px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label>Password</label>
                    </div>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary" style="display:block;text-align:center;width:100%">
                        Login
                    </button>
                </div>
            </form>

            <p style="margin-top:16px;font-size:.85rem;text-align:center">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:#2563eb">Create a new account</a>
            </p>
        </div>
    </div>

</div>
@endsection
