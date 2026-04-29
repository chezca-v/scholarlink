<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AIController extends Controller
{
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
        $cacheKey = 'ai-chat-response:' . md5(($user?->id ?? $request->ip()) . '|' . $profileFingerprint . '|' . Str::lower($message));

        if ($cachedReply = Cache::get($cacheKey)) {
            return response()->json([
                'reply' => $cachedReply,
                'cached' => true,
            ]);
        }

        $rateKey = 'ai-chat:' . ($user?->id ? 'user:' . $user->id : 'ip:' . $request->ip());

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return response()->json([
                'reply' => "I'm pausing Gemini requests for a bit to protect the free-tier quota. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($rateKey, 60);

        $name = $user?->first_name ?? $user?->name ?? 'Guest';
        $course = $profile?->course_program ?? 'not set';
        $university = $profile?->university_name ?? 'not set';
        $gpa = $profile?->gpa ?? 'not set';
        $income = $profile?->monthly_household_income ?? 'not set';

        $prompt = <<<PROMPT
You are Isko, ScholarLink's scholarship assistant.
Reply in plain text only. Keep it under 80 words unless the user asks for details.

Student: {$name}; course: {$course}; school: {$university}; GPA: {$gpa}; income: {$income}.

User: {$message}
PROMPT;

        $reply = Str::of($this->callGemini($prompt, 220))->trim()->toString()
            ?: 'I am here to help with scholarships. Could you rephrase your question?';

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
        $profile = $user->applicantProfile;
        $profileFingerprint = md5(json_encode([
            $profile?->gpa,
            $profile?->course_program,
            $profile?->university_name,
            $profile?->monthly_household_income,
            $profile?->province,
        ]));
        $formattedScholarships = $this->formatScholarships();
        $scholarshipSnapshot = md5($formattedScholarships);
        $cacheKey = 'ai-scholarship-match:' . $user->id . ':' . $profileFingerprint . ':' . $scholarshipSnapshot;

        if ($cachedMatches = Cache::get($cacheKey)) {
            return response()->json([
                'matches' => $cachedMatches,
                'cached' => true,
            ]);
        }

        $rateKey = 'ai-match:' . $user->id;

        if (RateLimiter::tooManyAttempts($rateKey, 2)) {
            $seconds = RateLimiter::availableIn($rateKey);

            return response()->json([
                'matches' => [],
                'message' => "I'm pausing new AI matching requests to prevent Gemini rate limits. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($rateKey, 60);

        $prompt = "
            You are a scholarship matching assistant for ScholarLink, a Philippine scholarship platform.

            Student Profile:
            - Name: {$user->first_name}
            - GPA: {$profile->gpa}
            - Course: {$profile->course_program}
            - University: {$profile->university_name}
            - Income Bracket: {$profile->monthly_household_income}
            - Location: {$profile->province}

            Based on this profile, give a match score from 0-100 and a short reason for each scholarship.
            Respond in JSON only, no markdown, like:
            [{ \"scholarship_id\": 1, \"score\": 92, \"reason\": \"High GPA match\" }]

            Scholarships to evaluate:
            " . $formattedScholarships;

        $matches = json_decode($this->callGemini($prompt), true);
        $matches = is_array($matches) ? $matches : [];

        if (! empty($matches)) {
            Cache::put($cacheKey, $matches, now()->addMinutes(15));
        }

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

    /**
     * Generate AI recommendation summary for the dashboard.
     */
    public function getDashboardSummary(Request $request)
    {
        $profile = $request->user()->applicantProfile;

        $prompt = "
            You are a friendly scholarship advisor for ScholarLink.
            Give a short 2-sentence encouragement and tip for a student named {$request->user()->first_name}
            who is taking {$profile->course_program} with a GPA of {$profile->gpa}.
            Keep it warm and motivating. No markdown.
        ";

        $text = $this->callGemini($prompt);

        return response()->json(['message' => $text]);
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
                Cache::put("gemini-key-cooldown:{$index}", true, now()->addMinute());
                continue;
            }

            return 'Unable to get AI response at this time.';
        }

        Cache::put('gemini-quota-cooldown', true, now()->addMinute());

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

        return $scholarships->map(fn ($scholarship) =>
            "ID:{$scholarship->id} | {$scholarship->name} by {$scholarship->provider_name} | Min GPA: {$scholarship->gpa_requirement} | Courses: " . (is_array($scholarship->courses) ? implode(', ', $scholarship->courses) : $scholarship->courses) . " | Income: {$scholarship->monthly_household_income}"
        )->implode("\n");
    }
}
