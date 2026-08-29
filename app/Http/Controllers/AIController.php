<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Services\ScholarshipScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AIController extends Controller
{
    public function __construct(
        private readonly ScholarshipScoringService $scoringService
    ) {}

    /**
     * Chat endpoint used by the floating chatbot widget.
     */
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = Str::of($validated['message'])->squish()->toString();
        $user = $request->user();
        $profile = $user?->applicantProfile;
        $profileFingerprint = md5(json_encode([
            $profile?->course_program,
            $profile?->university_name,
            $profile?->gpa,
            $profile?->monthly_household_income,
        ]));
        $cacheKey = 'ai-chat-response:'.md5(($user?->id ?? $request->ip()).'|'.$profileFingerprint.'|'.Str::lower($message));

        if ($cachedReply = Cache::get($cacheKey)) {
            return response()->json([
                'reply' => $cachedReply,
                'cached' => true,
            ]);
        }

        $rateKey = 'ai-chat:'.($user?->id ? 'user:'.$user->id : 'ip:'.$request->ip());

        if (RateLimiter::tooManyAttempts($rateKey, 1)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return response()->json([
                'reply' => "I'm pausing Gemini requests for a bit to protect the free-tier quota. Please try again in {$seconds} seconds.",
                'retry_after' => $seconds,
            ], 429);
        }

        RateLimiter::hit($rateKey, 30);

        $name = $user?->first_name ?? $user?->name ?? 'Guest';
        $course = $profile?->course_program ?? 'not set';
        $university = $profile?->university_name ?? 'not set';
        $gpa = $profile?->gpa ?? 'not set';
        $income = $profile?->monthly_household_income ?? 'not set';

        $prompt = <<<PROMPT
You are Scholar AI, ScholarLink's scholarship assistant.
Reply in plain text only. Keep it under 80 words unless the user asks for details.

Student: {$name}; course: {$course}; school: {$university}; GPA: {$gpa}; income: {$income}.

User: {$message}
PROMPT;

        $reply = Str::of($this->callGemini($prompt, 220))->trim()->toString()
            ?: 'I am here to help with scholarships. Could you rephrase your question?';

        $isRateLimitReply = Str::contains($reply, [
            'Gemini recently reported a quota or rate limit',
            'Gemini is available, but all configured API keys are currently rate-limited',
        ]);

        if ($isRateLimitReply) {
            return response()->json([
                'reply' => $reply,
                'retry_after' => 30,
            ], 429);
        }

        if (! Str::startsWith($reply, [
            'Gemini is available, but this API key',
            'Unable to get AI response',
        ])) {
            Cache::put($cacheKey, $reply, now()->addMinutes(20));
        }

        return response()->json([
            'reply' => $reply,
        ]);
    }

    /**
     * Match a student profile against available scholarships.
     */
    public function matchScholarships(Request $request)
    {
        $user = $request->user();
        $profile = $user?->applicantProfile;

        if (! $profile) {
            return response()->json([
                'matches' => [],
                'message' => 'Applicant profile is required for scholarship matching.',
            ], 422);
        }

        $profileFingerprint = md5(json_encode([
            $profile->gwa,
            $profile->course_program,
            $profile->university_name,
            $profile->monthly_household_income,
            $profile->province,
        ]));

        $scholarshipSnapshot = Scholarship::where('status', 'open')
            ->pluck('updated_at')
            ->implode(',');

        $cacheKey = 'scholarship-match:'.$user->id.':'.$profileFingerprint.':'.md5($scholarshipSnapshot);

        if ($cachedMatches = Cache::get($cacheKey)) {
            return response()->json([
                'matches' => $cachedMatches,
                'cached' => true,
            ]);
        }

        $scholarships = Scholarship::where('status', 'open')->get();

        $matches = $scholarships->map(function (Scholarship $scholarship) use ($profile) {
            $score = $this->scoringService->calculateMatchScore($profile, $scholarship);

            return [
                'scholarship_id' => $scholarship->id,
                'score' => $score,
                'reason' => $this->buildScholarshipMatchReason($profile, $scholarship, $score),
            ];
        })->sortByDesc('score')->values()->all();

        Cache::put($cacheKey, $matches, now()->addMinutes(15));

        return response()->json([
            'matches' => $matches,
        ]);
    }

    /**
     * Backward-compatible API route name.
     */
    public function match(Request $request)
    {
        return $this->matchScholarships($request);
    }

    private function calculateScholarshipScore($profile, Scholarship $scholarship): int
    {
        $gpaScore = $this->scoreGpa($profile->gwa, $scholarship->gpa_requirement);
        $courseScore = $this->scoreCourse($profile->course_program, $scholarship->courses);
        $incomeScore = $this->scoreIncome($profile->monthly_household_income, $scholarship->income_bracket);

        $score = (int) round(
            ($gpaScore * 0.55) +
            ($courseScore * 0.30) +
            ($incomeScore * 0.15)
        );

        return max(0, min(100, $score));
    }

    private function scoreGpa($profileGpa, $requirement): int
    {
        if (blank($requirement)) {
            return 100;
        }

        if (blank($profileGpa)) {
            return 50;
        }

        $profileGpa = floatval($profileGpa);
        $requirement = floatval($requirement);

        if ($profileGpa <= $requirement) {
            return 100;
        }

        $diff = max(0, $profileGpa - $requirement);

        return max(0, 100 - (int) round($diff * 30));
    }

    private function scoreCourse($profileCourse, $scholarshipCourses): int
    {
        if (blank($scholarshipCourses)) {
            return 100;
        }

        $profileCourse = Str::lower(trim((string) $profileCourse));
        $courses = is_array($scholarshipCourses) ? $scholarshipCourses : explode(',', (string) $scholarshipCourses);
        $courses = array_filter(array_map(fn ($course) => Str::lower(trim($course)), $courses));

        if (! $profileCourse || empty($courses)) {
            return 50;
        }

        foreach ($courses as $course) {
            if (! $course) {
                continue;
            }
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
        $threshold = $this->parseIncomeThreshold((string) $bracket);

        if (is_null($threshold)) {
            return 75;
        }

        return $annualIncome <= $threshold ? 100 : 20;
    }

    private function parseIncomeThreshold(string $bracket): ?int
    {
        if (preg_match_all('/\d+[\d,]*/', $bracket, $matches)) {
            $numbers = array_map(fn ($value) => (int) str_replace(',', '', $value), $matches[0]);
            if (! empty($numbers)) {
                return max($numbers);
            }
        }

        return null;
    }

    private function buildScholarshipMatchReason($profile, Scholarship $scholarship, int $score): string
    {
        $segments = [];

        if (blank($scholarship->gpa_requirement)) {
            $segments[] = 'No minimum GPA requirement.';
        } elseif ($profile->gwa <= $scholarship->gpa_requirement) {
            $segments[] = 'GPA meets the scholarship requirement.';
        } else {
            $segments[] = 'GPA may be above the scholarship requirement.';
        }

        if (blank($scholarship->courses)) {
            $segments[] = 'Open to all courses.';
        } elseif ($this->scoringService->scoreCourse($profile->course_program, $scholarship->courses) >= 90) {
            $segments[] = 'Course program is a strong match.';
        } elseif ($this->scoringService->scoreCourse($profile->course_program, $scholarship->courses) >= 50) {
            $segments[] = 'Course program may match this scholarship.';
        } else {
            $segments[] = 'Course program is not an ideal match.';
        }

        if (blank($scholarship->income_bracket)) {
            $segments[] = 'No income restriction.';
        } elseif ($this->scoringService->scoreIncome($profile->monthly_household_income, $scholarship->income_bracket) === 100) {
            $segments[] = 'Income meets the eligibility bracket.';
        } else {
            $segments[] = 'Income may exceed the scholarship bracket.';
        }

        $segments[] = "Overall fit score is {$score}.";

        return implode(' ', $segments);
    }

    /**
     * Generate AI recommendation insight for a specific scholarship detail page.
     */
    public function getScholarshipAIInsight(Request $request, $id)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Please log in to see your AI insights.']);
        }

        $profile = $user->applicantProfile;
        if (! $profile) {
            return response()->json(['message' => 'Complete your profile to see your AI insights.']);
        }

        $scholarship = Scholarship::find($id);
        if (! $scholarship) {
            return response()->json(['message' => 'Scholarship not found.']);
        }

        $cacheKey = "ai-insight:user-{$user->id}:scholarship-{$scholarship->id}";

        if ($cachedInsight = Cache::get($cacheKey)) {
            return response()->json([
                'message' => $cachedInsight,
                'cached' => true,
            ]);
        }

        $prompt = "
            You are a friendly scholarship advisor for ScholarLink.
            Give a short 2-sentence encouragement and tip for a student named {$user->first_name}
            who is taking {$profile->course_program} with a GPA of {$profile->gwa} and is applying for the '{$scholarship->name}' scholarship.
            Keep it warm and motivating. No markdown.
        ";

        try {
            $text = $this->callGemini($prompt);

            // Do not cache rate-limit or error messages
            if (! Str::contains($text, ['pausing new AI requests', 'rate-limited', 'Unable to get AI response'])) {
                // Cache for 180 days
                Cache::put($cacheKey, $text, now()->addDays(180));
            }

            return response()->json(['message' => $text]);
        } catch (\Exception $e) {
            Log::error('AI Insight Error: '.$e->getMessage());

            return response()->json(['message' => "AI insights are currently resting, but you're a great fit based on your profile!"]);
        }
    }

    /**
     * Core method: calls the Gemini REST API.
     */
    private function callGemini(string $prompt, int $maxOutputTokens = 512): string
    {
        $apiKeys = collect(config('services.gemini.keys', []))
            ->filter(fn ($key) => filled($key))
            ->values()
            ->all();
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-2.0-flash');

        if (blank($apiKey) && empty($apiKeys)) {
            return "Simulated AI Response: I'm currently running in local mode without a Gemini API key. But I am Scholar AI, your AI assistant. How can I help you today?";
        }

        if (Cache::has('gemini-quota-cooldown')) {
            return 'Gemini recently reported a quota or rate limit. I am pausing new AI requests briefly to protect your free-tier usage. Please try again in a minute.';
        }

        if (empty($apiKeys) && filled($apiKey)) {
            $apiKeys = [$apiKey];
        }

        $startIndex = (int) Cache::get('gemini-key-rr-index', 0);
        $keyCount = count($apiKeys);

        for ($attempt = 0; $attempt < $keyCount; $attempt++) {
            $index = ($startIndex + $attempt) % $keyCount;
            $currentKey = $apiKeys[$index];

            if (Cache::has("gemini-key-cooldown:{$index}")) {
                continue;
            }

            $response = Http::timeout(30)
                ->acceptJson()
                ->withQueryParameters(['key' => $currentKey])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => $maxOutputTokens,
                    ],
                ]);

            if ($response->successful()) {
                Cache::put('gemini-key-rr-index', ($index + 1) % $keyCount, now()->addHours(6));

                return (string) $response->json('candidates.0.content.parts.0.text', '');
            }

            Log::error('Gemini API error', [
                'key_index' => $index + 1,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->status() === 429) {
                Cache::put("gemini-key-cooldown:{$index}", true, now()->addSeconds(30));

                continue;
            }

            return 'Unable to get AI response at this time.';
        }

        Cache::put('gemini-quota-cooldown', true, now()->addSeconds(30));

        return 'Gemini is available, but all configured API keys are currently rate-limited. Please try again in about a minute.';
    }

    /**
     * Helper: format scholarships for the prompt.
     */
    private function formatScholarships(): string
    {
        $scholarships = Scholarship::where('status', 'open')
            ->select('id', 'name', 'provider_name', 'gpa_requirement', 'courses', 'income_bracket')
            ->get();

        return $scholarships->map(fn ($scholarship) => "ID:{$scholarship->id} | {$scholarship->name} by {$scholarship->provider_name} | Min GPA: {$scholarship->gpa_requirement} | Courses: ".(is_array($scholarship->courses) ? implode(', ', $scholarship->courses) : $scholarship->courses)." | Income: {$scholarship->monthly_household_income}"
        )->implode("\n");
    }
}
