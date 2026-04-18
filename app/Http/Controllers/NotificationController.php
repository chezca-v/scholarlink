<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() { return view('notifications.index'); }
    public function markRead($id) { /* Update read_at */ }
    public function markAllRead() { /* Update all for user */ }
}
