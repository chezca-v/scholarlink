<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    /**
     * AI match scoring API endpoint
     * Compares student profile against scholarship requirements.
     */
    public function match(Request $request)
    {
        // Logic for Gemini API integration will go here
        return response()->json([
            'status' => 'success',
            'match_score' => 85,
            'analysis' => 'Student meets GPA and residency requirements.'
        ]);
    }
}
