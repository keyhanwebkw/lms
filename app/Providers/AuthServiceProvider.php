<?php

namespace App\Providers;

use App\Guards\ChildrenGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::extend('children', function ($app, $name, array $config) {
            return new ChildrenGuard(Auth::createUserProvider($config['provider']), $app['request']);
        });
    }
}
