<?php

namespace App\Support;

use App\Models\User;

trait RedirectsUsersByRole
{
    protected function dashboardRouteFor(?User $user): string
    {
        return match ($user?->role) {
            'admin' => route('admin.dashboard', absolute: false),
            'evaluator' => route('evaluator.dashboard', absolute: false),
            'superadmin' => route('superadmin.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };
    }
}
