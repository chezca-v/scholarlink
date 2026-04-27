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
        return match ($user?->role) {
            'admin' => route('admin.dashboard', absolute: false),
            'evaluator' => route('evaluator.dashboard', absolute: false),
            'superadmin' => route('superadmin.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };
    }
}
