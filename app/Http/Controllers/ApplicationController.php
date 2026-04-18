<?php

namespace App\Http\Controllers;

use App\Models\Application; //
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('applicant.applications.index');
    }


    public function create()
    {
        return view('applicant.applications.create');
    }


    public function store(Request $request)
    {
        // Logic to save scholarship application
    }


    public function show($id)
    {
        return view('applicant.applications.show', compact('id'));
    }


    public function track($id)
    {
        return view('applicant.applications.track', compact('id'));
    }
}
