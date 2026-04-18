<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; //
use App\Models\Scholarship; //
use App\Models\Application; //

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function createUser(Request $request)
    {
        // Logic to create a new user with specific roles
    }

    public function deactivateUser($id)
    {
        // Logic to update user status to inactive
    }


    public function analytics()
    {
        return view('admin.analytics');
    }

    public function calendar()
    {
        return view('admin.calendar');
    }
}
