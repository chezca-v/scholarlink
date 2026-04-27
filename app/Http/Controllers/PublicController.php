<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship; // Import the Scholarship model for the browse methods

class PublicController extends Controller
{
    /**
     * Display the landing page (/).
     * This is the main entry point for all visitors.
     */
    public function landing()
    {
        // Fetch featured scholarships (open status, ordered by posted_at)
        $scholarships = Scholarship::where('status', 'open')
            ->orderBy('posted_at', 'desc')
            ->limit(6)
            ->get();

        return view('home', compact('scholarships')); // Renders the public landing page with scholarships
    }

    /**
     * Display the public list of scholarships (/scholarships).
     * Allows anyone to browse available opportunities.
     */
    public function index()
    {
        // For the stub, we just return the view
        return view('scholarships.index');
    }

    /**
     * Display details for a specific scholarship (/scholarships/{id}).
     * Accessible by guests to read requirements before registering.
     */
    public function show($id)
    {
        // For the stub, we return the detail view
        return view('scholarships.show', compact('id'));
    }
}
