<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Support\RedirectsUsersByRole;

class RegisteredUserController extends Controller
{
    use RedirectsUsersByRole;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.login', [
            'roles' => ['applicant', 'evaluator', 'admin', 'superadmin'],        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:applicant,evaluator,admin,superadmin'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'first_name' => $request->string('first_name')->toString(),
            'last_name' => $request->string('last_name')->toString(),
            'email' => $request->string('email')->lower()->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'role' => $request->string('role')->lower()->toString(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->dashboardRouteFor($user));
    }

    private function dashboardRouteFor(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard', absolute: false),
            'evaluator' => route('evaluator.dashboard', absolute: false),
            'superadmin' => route('superadmin.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };
    }
}
