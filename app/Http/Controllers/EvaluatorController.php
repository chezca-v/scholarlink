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

        $totalApplications = Application::count();
        $pendingReviews    = Application::where('status', 'pending')->count();

        $stats = [
            [
                'icon'        => '📋',
                'label'       => 'Pending Reviews',
                'value'       => $pendingReviews,
                'badge_text'  => 'Queue',
                'badge_color' => '#C9A84C',
                'footer'      => 'Applications awaiting review',
            ],
            [
                'icon'        => '✅',
                'label'       => 'Total Applications',
                'value'       => $totalApplications,
                'badge_text'  => 'All',
                'badge_color' => '#22889a',
                'footer'      => 'Across all scholarships',
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
        $applications = Application::with('scholarship')
            ->where('status', 'review')
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
