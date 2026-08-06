<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

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
        // 1. Suntikkan otomatis nilai default {role} ke helper route() di dalam Blade
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $role = auth()->user()->roles->first()->name ?? null;

                if ($role) {
                    URL::defaults(['role' => $role]);
                }
            }
        });
        Paginator::useTailwind();
    }
}
