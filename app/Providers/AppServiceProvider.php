<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Mail\CustomMailManager;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Rules\Recaptcha;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('mail.manager', function ($manager) {
            return new CustomMailManager($this->app);
        });
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

        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            return (new Recaptcha)->passes($attribute, $value);
        });
    }
}
