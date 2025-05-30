<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class RecaptchaServiceProvider extends ServiceProvider
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
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=" . config('recaptcha.secret_key') . "&response=" . $value . "&remoteip=" . request()->ip()
            );
            $response = json_decode($response);
            return $response->success;
        });
    }
}
