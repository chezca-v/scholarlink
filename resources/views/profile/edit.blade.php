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
</style>
@endpush
<div class="main-inner">
    <div class="header-row" style="margin-bottom: 24px;">
        <div class="page-title-area">
            <span class="page-eyebrow">ACCOUNT</span>
            <h1 class="page-title" style="font-family: 'Fraunces', serif; font-size: 28px; font-weight: 900; color: #0F4C5C;">My Profile</h1>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 24px; max-width: 800px;">
        <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(15, 76, 92, 0.03);">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(15, 76, 92, 0.03);">
            @include('profile.partials.update-password-form')
        </div>

        <div style="background: #fff; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(15, 76, 92, 0.03);">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
