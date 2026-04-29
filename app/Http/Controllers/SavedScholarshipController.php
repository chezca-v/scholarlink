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

    public function store(Request $request, $id)
    {
        $user = auth()->user();
        
        \App\Models\SavedScholarship::firstOrCreate([
            'user_id' => $user->id,
            'scholarship_id' => $id,
        ], [
            'saved_at' => now(),
        ]);
        
        return redirect()->route('applicant.saved')->with('success', 'Scholarship saved successfully!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        \App\Models\SavedScholarship::where('user_id', $user->id)
            ->where('scholarship_id', $id)
            ->delete();
            
        return back()->with('success', 'Scholarship removed from saved list.');
    }
}
