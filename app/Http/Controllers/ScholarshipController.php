<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ScholarshipController extends Controller
{
public function index(Request $request)
    {
        // Get user profile early for sorting and scoring
        $profile = null;
        if (Auth::check()) {
            $profile = Auth::user()->applicantProfile;
        }

        // Start query
        $query = Scholarship::query();

        // Apply search term (q)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('provider_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('benefits', 'like', "%{$search}%")
                ->orWhere('eligibility', 'like', "%{$search}%")
                ->orWhere('requirements', 'like', "%{$search}%");
            });
        }

// Apply status filter (multiple)
        if ($request->has('status')) {
            $query->whereIn('status', (array) $request->status);
        } else {
            // Default show open, closing_soon, coming_soon (show all active scholarships)
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

        // Apply GWA filter (Philippines 1.0 to 5.0 system where lower is better)
        // If an applicant has a GWA of 2.0, they can apply to scholarships requiring 2.0, 2.25, 2.5...
        // So we want scholarships where gpa_requirement >= applicant_gwa
        if ($request->filled('gwa')) {
            $gwa = (float) $request->gwa;
            $query->where(function($q) use ($gwa) {
                // Assuming GWA is <= 5.0
                $q->where('gpa_requirement', '>=', $gwa)
                  ->where('gpa_requirement', '<=', 5.0);
            });
        }

        // Apply Percentage filter (70 to 100 system where higher is better)
        // If an applicant has 85%, they can apply to scholarships requiring 85%, 80%, 75%...
        // So we want scholarships where gpa_requirement <= applicant_percentage
        if ($request->filled('percentage')) {
            $percentage = (float) $request->percentage;
            $query->where(function($q) use ($percentage) {
                // Assuming Percentage is > 5.0 to distinguish from GWA
                $q->where('gpa_requirement', '<=', $percentage)
                  ->where('gpa_requirement', '>', 5.0);
            });
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
        $sort = $request->get('sort', 'ai_match');

        // For AI-based sorting, we need to get IDs and scores first (limited approach for pagination)
        if ($sort === 'ai_match' && Auth::check() && $profile) {
            $allScholarships = Scholarship::whereIn('status', ['open', 'closing_soon', 'coming_soon'])->get();
            $allScores = $this->calculateMatchScores($profile, $allScholarships->all());
            arsort($allScores);
            $sortedIds = array_keys($allScores);

            // Use FIELD() for MySQL or CASE for SQLite to order by the calculated scores
            if (!empty($sortedIds)) {
                $driver = $query->getConnection()->getDriverName();
                if ($driver === 'sqlite') {
                    $cases = collect($sortedIds)->map(fn($id, $index) => "WHEN {$id} THEN {$index}")->implode(' ');
                    $query->orderByRaw("CASE id {$cases} ELSE " . count($sortedIds) . " END");
                } else {
                    $idsStr = implode(',', $sortedIds);
                    $query->orderByRaw("FIELD(id, {$idsStr})");
                }
            }
        } else {
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
                case 'ai_match':
                case 'match':
                default:
                    $query->latest('posted_at');
                    break;
            }
        }

        // Paginate (e.g., 12 per page)
        $scholarships = $query->paginate(12)->withQueryString();

        // Eager load applications for the current user (to show match score & bookmark status)
        if (Auth::check()) {
            $scholarships->load(['applications' => function ($q) {
                $q->where('applicant_id', Auth::id());
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

        // Prepare counts for the dashboard sidebar
        $statusCounts = [
            'open' => Scholarship::where('status', 'open')->count(),
            'closing soon' => Scholarship::where('status', 'closing_soon')->count(),
            'coming soon' => Scholarship::where('status', 'coming_soon')->count(),
            'closed' => Scholarship::where('status', 'closed')->count(),
        ];

        $incomeBrackets = Scholarship::select('income_bracket')->distinct()->whereNotNull('income_bracket')->pluck('income_bracket');

$applicationCount = 0;
        $savedCount = 0;
        $unreadCount = 0;
        $aiMatchScores = [];
        $topMatchId = null;

        if (Auth::check()) {
            $user = Auth::user();
            $applicationCount = \App\Models\Application::where('applicant_id', $user->id)->count();
            $savedCount = \App\Models\SavedScholarship::where('user_id', $user->id)->count();
            $unreadCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();

            // Calculate AI match scores for authenticated users with profile
            if ($profile) {
                $aiMatchScores = $this->calculateMatchScores($profile, $scholarships->items());
                // Find top match ID for highlighting
                if (!empty($aiMatchScores)) {
                    $topMatchId = array_keys($aiMatchScores, max($aiMatchScores))[0] ?? null;
                }
            }
        }

        return view('scholarships.index', compact(
            'scholarships', 'filters', 'statusCounts', 'incomeBrackets',
            'applicationCount', 'savedCount', 'unreadCount', 'aiMatchScores', 'topMatchId'
        ));
    }

    /**
     * Calculate AI match scores for scholarships based on user profile.
     */
    private function calculateMatchScores(ApplicantProfile $profile, array $scholarships): array
    {
        $scores = [];
        $gwa = $profile->gwa;
        $course = $profile->course_program;
        $income = $profile->monthly_household_income;

        foreach ($scholarships as $scholarship) {
            $gpaScore = $this->scoreGpa($gwa, $scholarship->gpa_requirement);
            $courseScore = $this->scoreCourse($course, $scholarship->courses);
            $incomeScore = $this->scoreIncome($income, $scholarship->income_bracket);

            $score = (int) round(
                ($gpaScore * 0.55) +
                ($courseScore * 0.30) +
                ($incomeScore * 0.15)
            );
            $scores[$scholarship->id] = max(0, min(100, $score));
        }

        return $scores;
    }

private function scoreGpa($profileGpa, $requirement): int
    {
        // Philippine grading scale: 1.0 = best, 5.0 = fail
        // 1.00 = 100, 1.25 = 96, 1.50 = 92, 1.75 = 88, 2.0 = 84, etc.
        if (blank($requirement) || blank($profileGpa)) {
            return blank($profileGpa) ? 50 : 100;
        }

        $profileGpa = floatval($profileGpa);
        $requirement = floatval($requirement);

        // With 1.0 = 100 scale, lower is better
        // If profile GPA <= requirement (e.g., 1.0 <= 1.5), student meets requirement
        if ($profileGpa <= $requirement) {
            return 100;
        }

        // Calculate score based on how much lower the requirement is
        // e.g., profile: 1.5, req: 1.0 → diff = 0.5 → score = 100 - (0.5 * 30) = 85
        $diff = max(0, $profileGpa - $requirement);
        return max(0, 100 - (int) round($diff * 30));
    }

    private function scoreCourse($profileCourse, $scholarshipCourses): int
    {
        if (blank($scholarshipCourses)) {
            return 100;
        }

        $profileCourse = Str::lower(trim((string) $profileCourse));
        $courses = is_array($scholarshipCourses)
            ? $scholarshipCourses
            : explode(',', (string) $scholarshipCourses);
        $courses = array_filter(array_map(fn($c) => Str::lower(trim($c)), $courses));

        if (!$profileCourse || empty($courses)) {
            return 50;
        }

        foreach ($courses as $course) {
            if (!$course) continue;
            if (Str::contains($profileCourse, $course) || Str::contains($course, $profileCourse)) {
                return 100;
            }
            $profileWords = preg_split('/\s+/', $profileCourse);
            foreach ($profileWords as $word) {
                if ($word && Str::contains($course, $word)) {
                    return 90;
                }
            }
        }

        return 0;
    }

    private function scoreIncome($monthlyIncome, $bracket): int
    {
        if (blank($bracket)) {
            return 100;
        }

        if (is_null($monthlyIncome)) {
            return 50;
        }

        $annualIncome = floatval($monthlyIncome) * 12;

        if (preg_match_all('/\d+[\d,]*/', (string) $bracket, $matches)) {
            $numbers = array_map(fn($v) => (int) str_replace(',', '', $v), $matches[0]);
            if (!empty($numbers)) {
                $threshold = max($numbers);
                return $annualIncome <= $threshold ? 100 : 20;
            }
        }

        return 75;
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

        // Calculate real slots remaining
        $approvedCount = \App\Models\Application::where('scholarship_id', $id)
            ->where('status', 'approved')
            ->count();
        $slotsRemaining = max(0, ($scholarship->slots ?? 0) - $approvedCount);

        return view('scholarships.show', compact('scholarship', 'slotsRemaining', 'approvedCount'));
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
