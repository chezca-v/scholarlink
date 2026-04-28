<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index() 
    { 
        $user = Auth::user();
        $profile = $user->applicantProfile;
        
        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();
            
        return view('applicant.notifications', compact('user', 'profile', 'notifications')); 
    }
    
    public function markRead($id) { 
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();
        return back();
    }
    
    public function markAllRead() { 
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return back();
    }
}
