<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register custom middleware aliases
        $this->app->alias(\App\Http\Middleware\AdminMiddleware::class, 'admin');
        $this->app->alias(\App\Http\Middleware\CustomerMiddleware::class, 'customer');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}