<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\Organization;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $scholarships = Scholarship::withCount('applications')->orderByDesc('created_at')->paginate(12);
        $scholarshipCount = Scholarship::count();
        return view('admin.scholarships.index', compact('scholarships', 'scholarshipCount'));
    }

    public function create()
    {
        $organizations = Organization::all();
        return view('admin.scholarships.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provider_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
            'gpa_requirement' => 'nullable|numeric|min:0|max:100',
            'income_bracket' => 'nullable|string|max:100',
            'slots' => 'required|integer|min:1',
            'eligibility' => 'required|string',
            'benefits' => 'required|string',
            'requirements' => 'required|string',
            'open_date' => 'required|date',
            'deadline' => 'required|date|after:open_date',
            'status' => 'required|in:open,closed,draft',
            'blind_screening' => 'boolean',
            'ai_match_enabled' => 'boolean',
            'weight_gpa' => 'nullable|numeric|min:0|max:100',
            'weight_income' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'contact_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['posted_at'] = now();

        Scholarship::create($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship created successfully.');
    }

    public function show($id, Request $request)
    {
        $scholarship = Scholarship::withCount('applications')->findOrFail($id);
        
        $query = Application::where('scholarship_id', $id)->with(['applicant']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'score_high') {
            $query->orderBy('ai_score', 'desc');
        } elseif ($sort === 'score_low') {
            $query->orderBy('ai_score', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $applications = $query->paginate(20)->withQueryString();

        $stageCounts = [
            'submitted' => Application::where('scholarship_id', $id)->where('status', 'submitted')->count(),
            'review' => Application::where('scholarship_id', $id)->where('status', 'review')->count(),
            'approved' => Application::where('scholarship_id', $id)->where('status', 'approved')->count(),
            'rejected' => Application::where('scholarship_id', $id)->where('status', 'rejected')->count(),
            'waitlisted' => Application::where('scholarship_id', $id)->where('status', 'waitlisted')->count(),
        ];
        
        // Ensure $evaluators is available for the assign modal
        $evaluators = \App\Models\User::where('role', 'evaluator')->get();

        return view('admin.scholarships.show', compact('scholarship', 'applications', 'stageCounts', 'evaluators'));
    }

    public function edit($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $organizations = Organization::all();
        return view('admin.scholarships.edit', compact('scholarship', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $request->validate([
            'name'             => 'required|string|max:255',
            'provider_name'    => 'required|string|max:255',
            'tagline'          => 'nullable|string|max:255',
            'description'      => 'required|string',
            'gpa_requirement'  => 'nullable|numeric|min:0|max:100',
            'income_bracket'   => 'nullable|string|max:100',
            'slots'            => 'required|integer|min:0',
            'eligibility'      => 'required|string',
            'benefits'         => 'required|string',
            'requirements'     => 'required|string',
            'open_date'        => 'required|date',
            'deadline'         => 'required|date',
            'status'           => 'required|in:open,closed,draft,closing_soon,coming_soon',
            'blind_screening'  => 'boolean',
            'ai_match_enabled' => 'boolean',
            'weight_gpa'       => 'nullable|numeric|min:0|max:100',
            'weight_income'    => 'nullable|numeric|min:0|max:100',
            'tags'             => 'nullable|array',
            'contact_email'    => 'nullable|email|max:255',
            'website'          => 'nullable|url|max:255',
            'address'          => 'nullable|string|max:500',
        ]);

        $data = $request->except(['_token', '_method']);
        // Ensure boolean fields
        $data['blind_screening']   = $request->boolean('blind_screening');
        $data['ai_match_enabled']  = $request->boolean('ai_match_enabled');

        $scholarship->update($data);

        return redirect()->route('admin.scholarships.index')
            ->with('success', '"'.$scholarship->name.'" updated successfully.');
    }

    public function destroy($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->delete();
        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship deleted.');
    }

    public function toggle($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->status = 'closed';
        $scholarship->save();
        return back()->with('success', 'Scholarship closed.');
    }

    public function exportApplications($id)
    {
        return back()->with('success', 'Applications exported successfully.');
    }

    public function extendDeadline(Request $request, $id)
    {
        $request->validate([
            'deadline_date' => 'required|date'
        ]);
        $scholarship = Scholarship::findOrFail($id);
        $scholarship->deadline_date = $request->deadline_date;
        $scholarship->save();
        return back()->with('success', 'Deadline extended.');
    }
}
