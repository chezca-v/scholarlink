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
            $profile?->course,
            $profile?->university_name,
            $profile?->gpa,
            $profile?->income_bracket,
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
        $course = $profile?->course ?? 'not set';
        $university = $profile?->university_name ?? 'not set';
        $gpa = $profile?->gpa ?? 'not set';
        $income = $profile?->income_bracket ?? 'not set';

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
        $profile = $request->user()->applicantProfile;

        $prompt = "
            You are a scholarship matching assistant for ScholarLink, a Philippine scholarship platform.

            Student Profile:
            - Name: {$request->user()->first_name}
            - GPA: {$profile->gpa}
            - Course: {$profile->course}
            - University: {$profile->university_name}
            - Income Bracket: {$profile->income_bracket}
            - Location: {$profile->region}

            Based on this profile, give a match score from 0-100 and a short reason for each scholarship.
            Respond in JSON only, no markdown, like:
            [{ \"scholarship_id\": 1, \"score\": 92, \"reason\": \"High GPA match\" }]

            Scholarships to evaluate:
            " . $this->formatScholarships();

        $response = $this->callGemini($prompt);

        return response()->json([
            'matches' => json_decode($response, true),
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
            who is taking {$profile->course} with a GPA of {$profile->gpa}.
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
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-2.0-flash');

        if (blank($apiKey)) {
            return "Simulated AI Response: I'm currently running in local mode without a Gemini API key. But I am Scholar AI, your AI assistant. How can I help you today?";
        }

        if (Cache::has('gemini-quota-cooldown')) {
            return 'Gemini recently reported a quota or rate limit. I am pausing new AI requests briefly to protect your free-tier usage. Please try again in a minute.';
        }

        $response = Http::timeout(30)
            ->acceptJson()
            ->withQueryParameters(['key' => $apiKey])
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

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->status() === 429) {
                Cache::put('gemini-quota-cooldown', true, now()->addMinute());

                return 'Gemini is available, but this API key has reached its current quota or rate limit. Please try again later or check your Google AI Studio quota/billing settings.';
            }

            return 'Unable to get AI response at this time.';
        }

        return (string) $response->json('candidates.0.content.parts.0.text', '');
    }

    /**
     * Helper: format scholarships for the prompt.
     */
    private function formatScholarships(): string
    {
        $scholarships = Scholarship::where('is_active', true)
            ->select('id', 'name', 'provider_name', 'min_gpa', 'course_requirements', 'income_requirement')
            ->get();

        return $scholarships->map(fn ($scholarship) =>
            "ID:{$scholarship->id} | {$scholarship->name} by {$scholarship->provider_name} | Min GPA: {$scholarship->min_gpa} | Courses: {$scholarship->course_requirements} | Income: {$scholarship->income_requirement}"
        )->implode("\n");
    }
}
