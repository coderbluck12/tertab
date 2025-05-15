<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('provide-a-reference', function(User $user)
        {
            return $user->role === 'lecturer';
        });

        Gate::define('request-for-reference', function(User $user)
        {
            return $user->role === 'student';
        });

        Gate::define('manage-platform', function(User $user)
        {
            return $user->role === 'admin';
        });
    }
}
