<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Organization;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Notification;
use Carbon\Carbon;

class EvaluatorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $unreadNotifications = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        // Get IDs of scholarships assigned to this evaluator
        $assignedScholarshipIds = $user->evaluatorAssignments()->pluck('scholarship_id');

        $totalApplications = Application::whereIn('scholarship_id', $assignedScholarshipIds)->count();
        $pendingReviews    = Application::whereIn('scholarship_id', $assignedScholarshipIds)
            ->where('status', 'under_review')
            ->count();

        $stats = [
            [
                'icon'        => '📋',
                'label'       => 'Pending Reviews',
                'value'       => $pendingReviews,
                'badge_text'  => 'Queue',
                'badge_color' => '#C9A84C',
                'footer'      => 'Awaiting your evaluation',
            ],
            [
                'icon'        => '✅',
                'label'       => 'Total Assigned',
                'value'       => $totalApplications,
                'badge_text'  => 'All',
                'badge_color' => '#22889a',
                'footer'      => 'Applications in your assigned pool',
            ],
        ];

        return view('evaluator.dashboard', compact(
            'now',
            'unreadNotifications',
            'stats'
        ));
    }

    public function queue()
    {
        $evaluator = auth()->user();
        $assignedScholarshipIds = $evaluator->evaluatorAssignments()->pluck('scholarship_id');

        $applications = Application::with('scholarship')
            ->whereHas('evaluations', function ($query) use ($evaluator) {
                $query->where('evaluator_id', $evaluator->id);
            })
            ->whereIn('status', ['under_review', 'revision'])
            ->paginate(15);
            
        return view('evaluator.queue', compact('applications'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->latest()->get();
        return view('evaluator.notifications', compact('user', 'notifications'));
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return back();
    }

    public function markRead($id)
    {
        Notification::where('user_id', Auth::id())->where('id', $id)->update(['is_read' => true]);
        return back();
    }

    public function profile()
    {
        return view('evaluator.profile', ['user' => Auth::user()]);
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
        
        $user->update($request->only(['first_name', 'last_name']));
        
        return back()->with('success', 'Profile updated.');
    }
}
