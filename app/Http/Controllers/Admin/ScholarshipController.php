<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Organization;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $adminId = auth()->id();
        $scholarships = Scholarship::where('created_by', $adminId)->withCount('applications')->orderByDesc('created_at')->paginate(12);
        $scholarshipCount = Scholarship::where('created_by', $adminId)->count();

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
            'status' => 'required|in:open,closed,draft,closing_soon,coming_soon',
            'blind_screening' => 'boolean',
            'ai_match_enabled' => 'boolean',
            'weight_gpa' => 'nullable|numeric|min:0|max:100',
            'weight_income' => 'nullable|numeric|min:0|max:100',
            'tags' => 'nullable|array',
            'contact_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'org_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['posted_at'] = now();

        // Map 'courses' or 'tags' to 'courses' column
        $data['courses'] = $request->courses ?? $request->tags;

        // Handle logo upload
        if ($request->hasFile('org_logo')) {
            $data['org_logo'] = $request->file('org_logo')->store('logos', 'public');
        }

        Scholarship::create($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship created successfully.');
    }

    public function show($id, Request $request)
    {
        $scholarship = Scholarship::where('created_by', auth()->id())->withCount('applications')->findOrFail($id);

        $query = Application::where('scholarship_id', $id)->with(['applicant.applicantProfile']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'score_high') {
            $query->orderBy('ai_match_score', 'desc');
        } elseif ($sort === 'score_low') {
            $query->orderBy('ai_match_score', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $applications = $query->paginate(20)->withQueryString();

        $stageCounts = [
            'submitted' => Application::where('scholarship_id', $id)->where('status', 'pending')->count(),
            'review' => Application::where('scholarship_id', $id)->where('status', 'under_review')->count(),
            'approved' => Application::where('scholarship_id', $id)->where('status', 'approved')->count(),
            'rejected' => Application::where('scholarship_id', $id)->where('status', 'rejected')->count(),
            'revision' => Application::where('scholarship_id', $id)->where('status', 'revision')->count(),
        ];

        $evaluators = \App\Models\User::where('role', 'evaluator')->get();

        return view('admin.scholarships.show', compact('scholarship', 'applications', 'stageCounts', 'evaluators'));
    }

    public function edit($id)
    {
        $scholarship = Scholarship::where('created_by', auth()->id())->findOrFail($id);
        $organizations = Organization::all();

        return view('admin.scholarships.edit', compact('scholarship', 'organizations'));
    }

    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::where('created_by', auth()->id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'provider_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'required|string',
            'gpa_requirement' => 'nullable|numeric|min:0|max:100',
            'income_bracket' => 'nullable|string|max:100',
            'slots' => 'required|integer|min:0',
            'eligibility' => 'required|string',
            'benefits' => 'required|string',
            'requirements' => 'required|string',
            'open_date' => 'required|date',
            'deadline' => 'required|date',
            'status' => 'required|in:open,closed,draft,closing_soon,coming_soon',
            'blind_screening' => 'boolean',
            'ai_match_enabled' => 'boolean',
            'weight_gpa' => 'nullable|numeric|min:0|max:100',
            'weight_income' => 'nullable|numeric|min:0|max:100',
            'courses' => 'nullable|array',
            'tags' => 'nullable|array',
            'contact_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'org_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['_token', '_method']);

        // Map 'courses' or 'tags' to 'courses' column
        $data['courses'] = $request->courses ?? $request->tags;

        // Ensure boolean fields
        $data['blind_screening'] = $request->boolean('blind_screening');
        $data['ai_match_enabled'] = $request->boolean('ai_match_enabled');

        // Handle logo upload
        if ($request->hasFile('org_logo')) {
            if ($scholarship->org_logo) {
                Storage::disk('public')->delete($scholarship->org_logo);
            }
            $data['org_logo'] = $request->file('org_logo')->store('logos', 'public');
        }

        $scholarship->update($data);

        return redirect()->route('admin.scholarships.index')
            ->with('success', '"'.$scholarship->name.'" updated successfully.');
    }

    public function destroy($id)
    {
        $scholarship = Scholarship::where('created_by', auth()->id())->findOrFail($id);
        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship deleted.');
    }

    public function toggle($id)
    {
        $scholarship = Scholarship::where('created_by', auth()->id())->findOrFail($id);
        $scholarship->status = 'closed';
        $scholarship->save();

        return back()->with('success', 'Scholarship closed.');
    }

    public function exportApplications($id)
    {
        $scholarship = Scholarship::where('created_by', auth()->id())->findOrFail($id);
        $applications = Application::where('scholarship_id', $id)
            ->with(['applicant.applicantProfile'])
            ->get();

        $filename = 'applications_'.Str::slug($scholarship->name).'_'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Ref Code', 'Applicant', 'Email', 'GPA', 'Course', 'Status', 'Submitted At'];

        $callback = function () use ($applications, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($applications as $app) {
                $profile = $app->applicant->applicantProfile;
                fputcsv($file, [
                    $app->reference_code,
                    $app->applicant->first_name.' '.$app->applicant->last_name,
                    $app->applicant->email,
                    $profile->gpa ?? 'N/A',
                    $profile->course_program ?? 'N/A',
                    $app->status,
                    $app->submitted_at ? $app->submitted_at->format('Y-m-d') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function extendDeadline(Request $request, $id)
    {
        $request->validate([
            'deadline' => 'required|date',
        ]);
        $scholarship = Scholarship::where('created_by', auth()->id())->findOrFail($id);
        $scholarship->deadline = $request->deadline;
        $scholarship->save();

        return back()->with('success', 'Deadline extended.');
    }
}
