@extends('layouts.applicant')

@section('title', 'ScholarLink - My Profile')

@section('content')
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
