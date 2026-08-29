<?php

namespace App\Http\Controllers;

class StaticPageController extends Controller
{
    public function about()
    {
        $stats = [
            'open' => \App\Models\Scholarship::where('status', 'open')->count(),
            'total' => \App\Models\Scholarship::count(),
            'applicants' => \App\Models\User::where('role', 'applicant')->count(),
        ];

        return view('static.about', compact('stats'));
    }

    public function organizations()
    {
        return view('static.organizations');
    }

    public function terms()
    {
        return view('static.terms');
    }

    public function privacy()
    {
        return view('static.privacy');
    }

    public function dataPrivacy()
    {
        return view('static.data-privacy');
    }
}
