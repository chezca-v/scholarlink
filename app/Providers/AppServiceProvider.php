<?php

namespace App\Providers;

use App\Models\Scholarship;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share scholarshipCount with the admin layout so the sidebar badge is always correct
        View::composer('admin.layouts.admin', function ($view) {
            $view->with('scholarshipCount', Scholarship::count());
        });
    }
}
