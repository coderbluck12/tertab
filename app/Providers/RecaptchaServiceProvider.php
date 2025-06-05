<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            try {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('recaptcha.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

                $result = $response->json();
                
                if (!$result['success']) {
                    Log::error('reCAPTCHA verification failed', [
                        'error_codes' => $result['error-codes'] ?? [],
                        'ip' => request()->ip()
                    ]);
                }

                return $result['success'];
            } catch (\Exception $e) {
                Log::error('reCAPTCHA verification error', [
                    'message' => $e->getMessage(),
                    'ip' => request()->ip()
                ]);
                return false;
            }
        });
    }
}
