<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    { // Start query
        $query = Scholarship::query();

        // Apply search term (q)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('provider_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply status filter (multiple)
        if ($request->has('status') && is_array($request->status)) {
            $query->whereIn('status', $request->status);
        } else {
            // Default show open, closing_soon, coming_soon
            $query->whereIn('status', ['open', 'closing_soon', 'coming_soon']);
        }

        // Apply category filter (tags JSON contains any of the categories)
        if ($request->has('category') && is_array($request->category)) {
            $query->where(function ($q) use ($request) {
                foreach ($request->category as $cat) {
                    $q->orWhere('tags', 'like', '%' . $cat . '%');
                }
            });
        }

        // Apply income bracket filter
        if ($request->has('income') && is_array($request->income)) {
            $query->whereIn('income_bracket', $request->income);
        }

        // Apply GPA filter (minimum requirement)
        if ($request->filled('gpa')) {
            $gpa = (float) $request->gpa;
            $query->where('gpa_requirement', '<=', $gpa);
        }

        // Apply deadline filter (relative to now)
        if ($request->filled('deadline')) {
            $now = now();
            switch ($request->deadline) {
                case 'This week':
                    $query->where('deadline', '<=', $now->copy()->endOfWeek())
                        ->where('deadline', '>=', $now);
                    break;
                case 'This month':
                    $query->where('deadline', '<=', $now->copy()->endOfMonth())
                        ->where('deadline', '>=', $now);
                    break;
                case 'Next 3 months':
                    $query->where('deadline', '<=', $now->copy()->addMonths(3))
                        ->where('deadline', '>=', $now);
                    break;
                // 'Any time' – no additional constraint
            }
        }

        // Apply match score filter (for authenticated users)
        if (Auth::check() && $request->filled('match')) {
            $minMatch = (int) $request->match;
            // You need to join the applications table if you store ai_match_score there
            // Or recalculate on the fly. Simpler approach: filter after pagination?
            // For performance, consider storing a computed match score column.
            // Here we'll assume you have a 'match_score' field on scholarships or a subquery.
            // If not, you may skip this filter or handle it in PHP after fetching.
            // For now, we'll add a placeholder – you'll need to adapt to your schema.
            // $query->whereHas('applications', function($q) use ($minMatch) {
            //     $q->where('ai_match_score', '>=', $minMatch)
            //       ->where('user_id', Auth::id());
            // });
        }

        // Apply sorting
        $sort = $request->get('sort', 'match');
        switch ($sort) {
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            case 'slots':
                $query->orderBy('slots', 'desc');
                break;
            case 'alpha':
                $query->orderBy('name', 'asc');
                break;
            case 'match':
            default:
                // If you have a computed match score for the logged-in user, order by that.
                // Otherwise fallback to latest.
                $query->latest('posted_at');
                break;
        }

        // Paginate (e.g., 12 per page)
        $scholarships = $query->paginate(12)->withQueryString();

        // Eager load applications for the current user (to show match score & bookmark status)
        if (Auth::check()) {
            $scholarships->load(['applications' => function ($q) {
                $q->where('user_id', Auth::id());
            }]);
        }

        // Store filters for the view (to populate the sidebar)
        $filters = [
            'q'        => $request->q,
            'status'   => $request->status,
            'category' => $request->category,
            'income'   => $request->income,
            'gpa'      => $request->gpa,
            'deadline' => $request->deadline,
            'match'    => $request->match,
            'sort'     => $sort,
        ];

        return view('scholarships.index', compact('scholarships', 'filters'));
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
        return view('admin.scholarships.edit', compact('id'));
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
