@extends('layouts.auth')

@section('content')
<div class="auth-wrapper">

    <div class="auth-left">
        <div>
            <h1>SafeDrive AI</h1>
            <p>
                Join the next generation of driver safety monitoring.
                Create your account to get started.
            </p>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2>Create Account</h2>
            <p>Fill in your details to register.</p>

            @if ($errors->any())
            <div style="color:#dc2626;font-size:14px;margin-bottom:12px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required autofocus>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min 8 characters" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary" style="display:block;text-align:center;width:100%">
                        Register
                    </button>
                </div>
            </form>

            <p style="margin-top:16px;font-size:.85rem;text-align:center">
                Already have an account?
                <a href="{{ route('login') }}" style="color:#2563eb">Login here</a>
            </p>
        </div>
    </div>

</div>
@endsection
