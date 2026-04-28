@extends('layouts.evaluator')

@section('page_title', 'My Profile')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="section-header" style="margin-bottom: 24px;">
        <div>
            <h2 class="section-title">Account Settings</h2>
            <small>Update your profile information and password.</small>
        </div>
    </div>

    @if(session('success'))
        <div style="background: var(--green-bg); color: var(--green); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 500; border: 1px solid #A7F3D0;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: var(--red-bg); color: var(--red); padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; font-weight: 500; border: 1px solid #FECACA;">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('evaluator.profile.update') }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">First Name <span class="req">*</span></label>
                <input type="text" name="first_name" class="form-input" value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Last Name <span class="req">*</span></label>
                <input type="text" name="last_name" class="form-input" value="{{ old('last_name', $user->last_name) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address <span class="req">*</span></label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
        </div>

        <hr style="border: none; border-top: 1px solid var(--border); margin: 24px 0;">

        <div style="margin-bottom: 16px;">
            <h3 style="font-size: 14px; font-weight: 700; color: var(--ink);">Change Password</h3>
            <small style="color: var(--slate);">Leave blank to keep your current password.</small>
        </div>

        <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-input" placeholder="••••••••">
        </div>

        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
