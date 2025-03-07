<?php

namespace App\Providers;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        VerifyCsrfToken::except([
            'fastapi-schedule'
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyCsrfToken::except([
            'fastapi-schedule'
        ]);
    }
}
