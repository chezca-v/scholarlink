<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedScholarshipController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $savedScholarships = $user->savedScholarships()
            ->with('scholarship')
            ->latest('saved_at')
            ->get();
            
        // We might also need notifications if the layout requires it
        $notifications = $user->notifications()->latest()->get();

        return view('applicant.saved', compact('user', 'savedScholarships', 'notifications'));
    }

    public function store(Request $request) { /* Save to saved_scholarships table */ }
    public function destroy($id) { /* Remove from saved_scholarships table */ }
}
