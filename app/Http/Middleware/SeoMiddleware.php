<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();
        $seoConfig = config('seo');
        
        // Determine which page config to use
        $pageKey = $this->getPageKey($routeName);
        $pageConfig = $seoConfig['pages'][$pageKey] ?? [];
        
        // Merge with defaults
        $seoData = array_merge($seoConfig['defaults'], $pageConfig);
        
        // Share SEO data with all views
        View::share('seoData', $seoData);
        
        return $next($request);
    }
    
    /**
     * Get the page key based on route name
     */
    private function getPageKey($routeName)
    {
        $routeMapping = [
            'welcome' => 'home',
            'login' => 'login',
            'register' => 'register',
            'affiliate.index' => 'affiliate',
            'dashboard' => 'dashboard',
            'admin.dashboard' => 'admin',
        ];
        
        return $routeMapping[$routeName] ?? 'home';
    }
}
