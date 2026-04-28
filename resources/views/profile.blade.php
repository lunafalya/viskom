@extends('layouts.app')

@section('content')

<style>
.profile-page{
    padding: 10px;
}

.profile-header-card,
.profile-box,
.security-box{
    background: #fff;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 6px 20px rgba(0,0,0,.04);
    border: 1px solid #eef2f7;
}

.profile-grid{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-top: 22px;
}

.profile-box h3{
    font-size: 18px;
    margin-bottom: 20px;
    color: #111827;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 12px;
}

.profile-top{
    display: flex;
    gap: 25px;
    align-items: center;
}

.avatar{
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid #f3f4f6;
}

.badge{
    display: inline-block;
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 14px;
    margin-right: 10px;
    margin-top: 12px;
}

.badge-green{
    background: #dcfce7;
    color: #15803d;
}

.badge-blue{
    background: #dbeafe;
    color: #1d4ed8;
}

.form-group{
    margin-bottom: 16px;
}

.form-row{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

label{
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    color: #374151;
}

input,select{
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    background: #f8fafc;
    font-size: 15px;
}

.security-box{
    margin-top: 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #eff6ff;
}

.btn-outline{
    padding: 14px 26px;
    border: 2px solid #1d4ed8;
    color: #1d4ed8;
    background: white;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
}

.footer-note{
    margin-top: 35px;
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

@media(max-width:1000px){
    .profile-grid{
        grid-template-columns: 1fr;
    }

    .profile-top{
        flex-direction: column;
        text-align: center;
    }

    .form-row{
        grid-template-columns: 1fr;
    }

    .security-box{
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }
}
</style>

<div class="profile-page">

    {{-- HEADER --}}
    <div class="profile-header-card">
        <div class="profile-top">

            <img src="{{ asset('img/edit.jpg') }}" class="avatar">

            <div>
                <h2 style="font-size:40px;margin-bottom:8px;">Marcus Vance</h2>
                <p style="font-size:20px;color:#6b7280;">
                    Senior Fleet Operations • Active for 4 years
                </p>

                <span class="badge badge-green">Active Status</span>
                <span class="badge badge-blue">Safe Score: 98%</span>
            </div>

        </div>
    </div>


    {{-- TWO COLUMN --}}
    <div class="profile-grid">

        {{-- PERSONAL --}}
        <div class="profile-box">
            <h3>👤 PERSONAL DETAILS</h3>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" value="Marcus Vance">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" value="m.vance@safedrive-ops.com">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" value="+1 (555) 904-2231">
                </div>

                <div class="form-group">
                    <label>License Number</label>
                    <input type="text" value="DL-8892011">
                </div>
            </div>
        </div>


        {{-- PROFESSIONAL --}}
        <div class="profile-box">
            <h3>💼 PROFESSIONAL DETAILS</h3>

            <div class="form-group">
                <label>Employment Status</label>
                <select>
                    <option>Full-Time Contract</option>
                </select>
            </div>

            <div class="form-group">
                <label>Fleet ID</label>
                <input type="text" value="SD-9042">
            </div>

            <div class="form-group">
                <label>Years of Experience</label>
                <input type="text" value="12">
            </div>
        </div>

    </div>


    {{-- EMERGENCY --}}
    <div class="profile-box" style="margin-top:22px;">
        <h3>🚨 EMERGENCY CONTACT</h3>

        <div class="form-row" style="grid-template-columns:1fr 1fr 1fr;">
            <div class="form-group">
                <label>Contact Name</label>
                <input type="text" value="Sarah Vance">
            </div>

            <div class="form-group">
                <label>Relationship</label>
                <input type="text" value="Spouse">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" value="+1 (555) 123-4567">
            </div>
        </div>
    </div>


    {{-- SECURITY --}}
    <div class="security-box">

        <div>
            <h3 style="margin-bottom:5px;">🛡 Biometric Authentication</h3>
            <p style="color:#2563eb;">
                Your profile data is encrypted with enterprise-grade security.
            </p>
        </div>

        <!-- <a href="#" class="btn-outline">
            Update Password
        </a> -->

    </div>

    <div class="footer-note">
        © 2024 SafeDrive AI Vigilance Systems. All Rights Reserved.
    </div>

</div>

@endsection