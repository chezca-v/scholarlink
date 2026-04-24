<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
use App\Models\ActivityLog;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }

    public function organizations()
    {
        return view('superadmin.organizations');
    }

    public function admins()
    {
        return view('superadmin.admins');
    }

    public function logs()
    {
        return view('superadmin.logs');
    }

    public function settings()
    {
        return view('superadmin.settings');
    }
}
