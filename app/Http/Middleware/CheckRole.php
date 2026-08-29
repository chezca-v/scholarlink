<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            // Redirect based on role if they try to access the wrong area
            $userRole = $request->user()?->role;

            $redirectPath = match ($userRole) {
                'admin' => '/admin/dashboard',
                'evaluator' => '/evaluator/dashboard',
                'superadmin' => '/superadmin/dashboard',
                default => '/dashboard', // Applicants and any other role
            };

            return redirect($redirectPath);
        }

        return $next($request);
    }
}
