<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'applicant') {
            $profile = $user->applicantProfile;

            if (! $profile || ! $profile->profile_completed_at) {
                // Allow access to profile setup routes to avoid infinite loop
                if (! $request->routeIs('profile.setup*') && ! $request->routeIs('logout')) {
                    return redirect()->route('profile.setup')->with('warning', 'Please complete your profile setup first.');
                }
            }
        }

        return $next($request);
    }
}
