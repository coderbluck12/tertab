<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // If user is not verified and not already on the verification page
        if ($user->status === 'pending' && !$request->routeIs('verification.*')) {
            return redirect()->route('verification.required');
        }

        // If user is verified and trying to access verification page
        if ($user->status === 'verified' && $request->routeIs('verification.*')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
} 