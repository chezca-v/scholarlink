@extends('layouts.applicant')

@section('title', 'ScholarLink - My Profile')

@section('content')
@push('styles')
<style>
/* Override default breeze forms */
input[type="text"], input[type="email"], input[type="password"] {
    width: 100%; padding: 10px 14px; border: 1.5px solid #dce9ed; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 13px; color: #1C1C2E; background: #fff; outline: none; transition: all .15s; margin-top: 6px;
}
input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
    border-color: #2A8FA0; box-shadow: 0 0 0 3px rgba(42,143,160,0.12);
}
label { font-size: 12px; font-weight: 600; color: #1C1C2E; }
button[type="submit"], .btn-primary {
    background: linear-gradient(135deg, #E8A838, #F9D679); color: #0F4C5C; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700; border: none; border-radius: 10px; padding: 10px 20px; cursor: pointer; transition: all .15s; margin-top: 16px;
}
button[type="submit"]:hover, .btn-primary:hover {
    background: #F9D679; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(232,168,56,0.3);
}
.text-red-600 { color: #e53e3e; }
.text-sm { font-size: 13px; }
.text-gray-900 { color: #1C1C2E; }
.text-gray-600 { color: #8A95A3; }
.mt-1 { margin-top: 4px; }
.mt-2 { margin-top: 8px; }
.mt-6 { margin-top: 24px; }
.space-y-6 > * + * { margin-top: 24px; }
h2.text-lg { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: #0F4C5C; margin-bottom: 4px; }
.flex.items-center.gap-4 { display: flex; align-items: center; gap: 16px; }
.underline { text-decoration: underline; color: #2A8FA0; background: none; border: none; cursor: pointer; padding: 0; font-size: 12px; }

/* Profile View Styles */
.profile-hero {
    background: linear-gradient(160deg, #0F4C5C, #2A8FA0);
    border-radius: 20px;
    padding: 32px;
    display: flex;
    align-items: center;
    gap: 24px;
    color: white;
    margin-bottom: 24px;
    box-shadow: 0 10px 30px rgba(15, 76, 92, 0.15);
}
.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #E8A838, #F9D679);
    color: #0F4C5C;
    font-size: 32px;
    font-weight: 700;
    font-family: 'Fraunces', serif;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid rgba(255, 255, 255, 0.2);
    flex-shrink: 0;
}
.profile-name { font-family: 'Fraunces', serif; font-size: 28px; font-weight: 700; margin-bottom: 4px; }
.profile-sub { font-size: 14px; opacity: 0.9; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
.profile-tags { display: flex; gap: 8px; margin-top: 12px; }
.profile-tag { background: rgba(255, 255, 255, 0.15); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

.info-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(15, 76, 92, 0.03);
    margin-bottom: 24px;
}
.info-title { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 700; color: #0F4C5C; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.info-item { display: flex; flex-direction: column; gap: 4px; }
.info-label { font-size: 11px; font-weight: 700; color: #8A95A3; text-transform: uppercase; letter-spacing: 0.5px; }
.info-value { font-size: 14px; font-weight: 600; color: #1C1C2E; }

.tabs { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid #E2E8F0; padding-bottom: 12px; }
.tab-btn { padding: 8px 16px; font-size: 14px; font-weight: 600; color: #8A95A3; background: none; border: none; cursor: pointer; border-radius: 8px; transition: all 0.2s; }
.tab-btn:hover { background: #f8fafc; color: #0F4C5C; }
.tab-btn.active { background: #E8F4F7; color: #0F4C5C; }
</style>
@endpush

<div class="main-inner" x-data="{ activeTab: 'overview' }">
    @php $profile = auth()->user()->applicantProfile; @endphp
    <div class="header-row" style="margin-bottom: 24px;">
        <div class="page-title-area">
            <span class="page-eyebrow">ACCOUNT</span>
            <h1 class="page-title" style="font-family: 'Fraunces', serif; font-size: 28px; font-weight: 900; color: #0F4C5C;">My Profile</h1>
        </div>
    </div>

    <!-- Hero Banner -->
    <div class="profile-hero">
        <div class="profile-avatar">
            {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1) . substr(auth()->user()->last_name ?? '', 0, 1)) }}
        </div>
        <div>
            <h2 class="profile-name">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</h2>
            <div class="profile-sub">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                {{ auth()->user()->email ?? 'user@example.com' }}
            </div>
            <div class="profile-tags">
                <span class="profile-tag">{{ $profile->year_level ? 'Year '.$profile->year_level : 'Applicant' }}</span>
                @if($profile?->course_program)
                <span class="profile-tag">{{ $profile->course_program }}</span>
                @endif
                @if($profile?->university_name)
                <span class="profile-tag" style="font-size:10px;">{{ $profile->university_name }}</span>
                @endif
            </div>
        </div>
        <div style="margin-left: auto;">
            <button @click="activeTab = 'edit'" class="btn-primary" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3); margin-top: 0;">Edit Profile</button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn" :class="{ 'active': activeTab === 'overview' }" @click="activeTab = 'overview'">Overview</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'edit' }" @click="activeTab = 'edit'">Edit Profile</button>
        <button class="tab-btn" :class="{ 'active': activeTab === 'security' }" @click="activeTab = 'security'">Security & Settings</button>
    </div>

    <!-- Tab Content: Overview -->
    <div x-show="activeTab === 'overview'" x-transition.opacity>
        <div class="info-card">
            <h3 class="info-title">Personal Details</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <span class="info-value">{{ auth()->user()->email ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone Number</span>
                    <span class="info-value">
                        {{ optional($profile)->mobile_number ?? 'Not provided' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Home Address</span>
                    <span class="info-value">
                        {{ collect([optional($profile)->home_address, optional($profile)->city, optional($profile)->province])->filter()->implode(', ') ?: 'Not provided' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date of Birth</span>
                    <span class="info-value">{{ optional($profile)->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('M d, Y') : 'Not provided' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sex</span>
                    <span class="info-value">{{ optional($profile)->sex ?? 'Not provided' }}</span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h3 class="info-title">Academic Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">University/School</span>
                    <span class="info-value">{{ optional($profile)->university_name ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Degree/Program</span>
                    <span class="info-value">{{ optional($profile)->course_program ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Year Level</span>
                    <span class="info-value">{{ optional($profile)->year_level ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Latest GPA/GWA</span>
                    <span class="info-value">{{ optional($profile)->gwa ? number_format(optional($profile)->gwa, 2) : 'Not set' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Edit Profile -->
    <div x-show="activeTab === 'edit'" x-transition.opacity style="display: none;">
        <div class="info-card max-w-3xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Tab Content: Security -->
    <div x-show="activeTab === 'security'" x-transition.opacity style="display: none;">
        <div class="info-card max-w-3xl">
            @include('profile.partials.update-password-form')
        </div>

        <div class="info-card max-w-3xl">
            <h3 class="info-title">Notification Preferences</h3>
            <p style="font-size: 13px; color: #8A95A3; margin-bottom: 16px;">Manage how you receive alerts about your applications.</p>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 12px;">
                <div>
                    <div style="font-weight: 600; font-size: 14px; color: #0F4C5C;">Email Notifications</div>
                    <div style="font-size: 12px; color: #8A95A3;">Receive updates via email.</div>
                </div>
                <div style="width: 44px; height: 24px; background: #1a9653; border-radius: 20px; position: relative; cursor: pointer;">
                    <div style="width: 20px; height: 20px; background: white; border-radius: 50%; position: absolute; top: 2px; right: 2px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                <div>
                    <div style="font-weight: 600; font-size: 14px; color: #0F4C5C;">SMS Alerts</div>
                    <div style="font-size: 12px; color: #8A95A3;">Get text messages for important deadlines.</div>
                </div>
                <button type="button" x-data @click="$dispatch('open-modal', 'sms-optin')" style="padding: 6px 12px; font-size: 12px; font-weight: 600; color: #0F4C5C; background: white; border: 1px solid #dce9ed; border-radius: 6px; cursor: pointer;">Configure SMS</button>
            </div>
        </div>

        <div class="info-card max-w-3xl" style="border-color: #fecaca; background: #fffcfc;">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>

@include('profile.partials.sms-optin-modal')
@endsection
