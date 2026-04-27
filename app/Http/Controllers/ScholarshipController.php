<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'provider_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
            'gpa_requirement' => 'nullable|numeric|min:0|max:5',
            'income_bracket' => 'nullable|string|max:100',
            'slots' => 'required|integer|min:1',
            'eligibility' => 'required|string',
            'benefits' => 'required|string',
            'requirements' => 'required|string',
            'open_date' => 'required|date',
            'deadline' => 'required|date|after:open_date',
            'status' => 'required|in:open,closed,draft',
            'blind_screening' => 'boolean',
            'weight_gpa' => 'nullable|numeric|min:0|max:100',
            'weight_income' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'ai_match_enabled' => 'boolean',
            'contact_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'benefit_snippet_1' => 'nullable|string|max:255',
            'benefit_snippet_2' => 'nullable|string|max:255',
            'org_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['posted_at'] = now(); // Or based on status

        // Handle logo upload if provided
        if ($request->hasFile('org_logo')) {
            $data['org_logo'] = $request->file('org_logo')->store('logos', 'public');
        }

        Scholarship::create($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship created successfully.');
    }

    public function show($id)
    {
        $scholarship = Scholarship::withCount('applications')->findOrFail($id);
        return view('scholarships.show', compact('scholarship'));
    }

    public function edit($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $request->validate([
            // Same rules as store, but make some optional for updates
            'provider_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
            'gpa_requirement' => 'nullable|numeric|min:0|max:5',
            'income_bracket' => 'nullable|string|max:100',
            'slots' => 'required|integer|min:1',
            'eligibility' => 'required|string',
            'benefits' => 'required|string',
            'requirements' => 'required|string',
            'open_date' => 'required|date',
            'deadline' => 'required|date|after:open_date',
            'status' => 'required|in:open,closed,draft',
            'blind_screening' => 'boolean',
            'weight_gpa' => 'nullable|numeric|min:0|max:100',
            'weight_income' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'ai_match_enabled' => 'boolean',
            'contact_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'benefit_snippet_1' => 'nullable|string|max:255',
            'benefit_snippet_2' => 'nullable|string|max:255',
            'org_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Handle logo upload if provided
        if ($request->hasFile('org_logo')) {
            // Delete old logo if exists
            if ($scholarship->org_logo) {
                Storage::disk('public')->delete($scholarship->org_logo);
            }
            $data['org_logo'] = $request->file('org_logo')->store('logos', 'public');
        }

        $scholarship->update($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship updated successfully.');
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
