<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Redirect based on role if they try to access the wrong area
            return match($request->user()?->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'evaluator' => redirect()->route('evaluator.dashboard'),
                'superadmin' => redirect()->route('superadmin.dashboard'),
                default => redirect()->route('dashboard'), // Applicants
            };
        }

        return $next($request);
    }
}
