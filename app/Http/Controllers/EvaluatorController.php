<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\EvaluatorAssignment;
use App\Models\Evaluation;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EvaluatorController extends Controller
{
    public function dashboard()
    {
        $evaluator = auth()->user();

        // Assigned scholarships with queue counts
        $assignments = EvaluatorAssignment::query()
            ->where('evaluator_id', $evaluator->id)
            ->with('scholarship')
            ->get();

        $assignedScholarshipIds = $assignments->pluck('scholarship_id');

        // Pending queue count per scholarship
        $queueCounts = Application::query()
            ->whereIn('scholarship_id', $assignedScholarshipIds)
            ->whereIn('status', ['pending', 'under_review'])
            ->selectRaw('scholarship_id, COUNT(*) as total')
            ->groupBy('scholarship_id')
            ->pluck('total', 'scholarship_id');

        // Workload bar — total assigned vs completed
        $totalAssigned = Application::query()
            ->whereIn('scholarship_id', $assignedScholarshipIds)
            ->count();

        $totalCompleted = Evaluation::query()
            ->where('evaluator_id', $evaluator->id)
            ->whereNotNull('decision')
            ->count();

        // Recent completions
        $recentCompletions = Evaluation::query()
            ->where('evaluator_id', $evaluator->id)
            ->whereNotNull('decision')
            ->with('application.scholarship')
            ->latest('evaluated_at')
            ->take(5)
            ->get();

        return view('evaluator.dashboard', [
            'assignments'      => $assignments,
            'queueCounts'      => $queueCounts,
            'totalAssigned'    => $totalAssigned,
            'totalCompleted'   => $totalCompleted,
            'recentCompletions' => $recentCompletions,
        ]);
    }

    public function queue()
    {
        $evaluator = auth()->user();

        $assignedScholarshipIds = EvaluatorAssignment::query()
            ->where('evaluator_id', $evaluator->id)
            ->pluck('scholarship_id');

        $applications = Application::query()
            ->whereIn('scholarship_id', $assignedScholarshipIds)
            ->whereIn('status', ['pending', 'under_review'])
            ->with(['scholarship', 'applicant.applicantProfile'])
            ->oldest('submitted_at')
            ->paginate(15);

        return view('evaluator.queue', [
            'applications' => $applications,
        ]);
    }

    public function notifications()
    {
        $user = auth()->user();
        
        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();
            
        return view('evaluator.notifications', compact('notifications', 'user'));
    }
    
    public function markRead($id) 
    { 
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();
        return back();
    }
    
    public function markAllRead() 
    { 
        Notification::where('user_id', auth()->id())->update(['is_read' => true]);
        return back();
    }

    public function profile()
    {
        $user = auth()->user();
        return view('evaluator.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('evaluator.profile')->with('success', 'Profile updated successfully.');
    }
}
