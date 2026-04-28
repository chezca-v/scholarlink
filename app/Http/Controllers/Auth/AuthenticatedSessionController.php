<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on the user's role
        return redirect()->intended($this->dashboardRouteFor($request->user()));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Determine the correct dashboard route based on the user's role.
     */
    private function dashboardRouteFor(?User $user): string
    {
        // Try to use named route if it exists, otherwise use path
        try {
            return match ($user?->role) {
                'admin' => route('admin.dashboard', [], false),
                'evaluator' => route('evaluator.dashboard', [], false),
                'superadmin' => route('superadmin.dashboard', [], false),
                'applicant' => route('applicant.dashboard', [], false),
                default => route('dashboard', [], false),
            };
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
            // Fallback to paths if route names don't exist
            return match ($user?->role) {
                'admin' => '/admin/dashboard',
                'evaluator' => '/evaluator/dashboard',
                'superadmin' => '/superadmin/dashboard',
                'applicant' => '/applicant/dashboard',
                default => '/dashboard',
            };
        }
    }
}
