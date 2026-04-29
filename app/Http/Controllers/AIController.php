<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AIController extends Controller
{
    /**
     * Chat endpoint used by the floating chatbot widget.
     */
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-1.5-flash');

        if (blank($apiKey)) {
            return response()->json([
                'reply' => 'Chat service is not configured yet. Please set GEMINI_API_KEY.',
            ], 503);
        }

        $systemInstruction = 'You are Scholar, a helpful scholarship assistant for students. '
            .'Keep answers concise, actionable, and friendly.';

        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            $model
        );

        $response = Http::timeout(20)
            ->acceptJson()
            ->withQueryParameters(['key' => $apiKey])
            ->post($endpoint, [
                'contents' => [[
                    'role' => 'user',
                    'parts' => [[
                        'text' => $validated['message'],
                    ]],
                ]],
                'systemInstruction' => [
                    'parts' => [[
                        'text' => $systemInstruction,
                    ]],
                ],
                'generationConfig' => [
                    'temperature' => 0.6,
                    'maxOutputTokens' => 512,
                ],
            ]);

        if (! $response->ok()) {
            return response()->json([
                'reply' => 'I could not reach Gemini right now. Please try again in a moment.',
            ], 502);
        }

        $reply = data_get($response->json(), 'candidates.0.content.parts.0.text');
        $fallback = 'I am here to help with scholarships. Could you rephrase your question?';

        return response()->json([
            'reply' => Str::of((string) ($reply ?: $fallback))->trim()->toString(),
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
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $user = $request->user();
        
        if ($user) {
            $profile = $user->applicantProfile;
            $course = $profile->course ?? 'not set';
            $uni = $profile->university_name ?? 'not set';
            $gpa = $profile->gpa ?? 'not set';
            $income = $profile->income_bracket ?? 'not set';
            $name = $user->first_name ?? $user->name;
        } else {
            $course = 'not set';
            $uni = 'not set';
            $gpa = 'not set';
            $income = 'not set';
            $name = 'Guest';
        }

        $prompt = "
            You are Scholar, a friendly AI scholarship assistant for ScholarLink —
            a Philippine scholarship platform. Keep answers concise (2-4 sentences max).

            Student context:
            - Name: {$name}
            - Course: {$course}
            - University: {$uni}
            - GPA: {$gpa}
            - Income bracket: {$income}

            User message: {$request->message}

            Reply helpfully and in a warm, encouraging tone.
            If asked about scholarships, refer to ScholarLink's browse page.
            Do not use markdown. Plain text only.
        ";

        $reply = $this->callGemini($prompt);

        return response()->json(['reply' => $reply]);
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
     * Core method — calls the Gemini REST API.
     */
    private function callGemini(string $prompt): string
    {
        if (empty($this->apiKey)) {
            return "Simulated AI Response: I'm currently running in local mode without a Gemini API key. But I am Scholar, your AI assistant! How can I help you today?";
        }

        $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::timeout(30)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 1024,
            ],
        ]);

        if ($response->failed()) {
            \Log::error('Gemini API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return 'Unable to get AI response at this time.';
        }

        return $response->json('candidates.0.content.parts.0.text', '');
    }

    /**
     * Helper — format scholarships for the prompt.
     */
    private function formatScholarships(): string
    {
        $scholarships = \App\Models\Scholarship::where('is_active', true)
            ->select('id', 'name', 'provider_name', 'min_gpa', 'course_requirements', 'income_requirement')
            ->get();

        return $scholarships->map(fn($s) =>
            "ID:{$s->id} | {$s->name} by {$s->provider_name} | Min GPA: {$s->min_gpa} | Courses: {$s->course_requirements} | Income: {$s->income_requirement}"
        )->implode("\n");
    }
}
