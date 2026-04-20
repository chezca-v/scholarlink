<?php

namespace App\Http\Controllers;

use App\Models\Scholarship; //
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        return view('scholarships.index');
    }

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(Request $request)
    {
        // Logic to save scholarship details
    }


    public function show($id)
    {
        $scholarship = \App\Models\Scholarship::findOrFail($id);
        return view('scholarships.show', compact('scholarship'));
    }


    public function edit($id)
    {
        return view('admin.scholarships.edit', compact('id'));
    }


    public function update(Request $request, $id)
    {
        // Logic to update scholarship
    }


    public function destroy($id)
    {
        // Logic to delete scholarship
    }


    public function close($id)
    {
        // Logic to change status to 'closed'
    }

    public function extendDeadline(Request $request, $id)
    {
        // Logic to update the 'deadline' field
    }
}
