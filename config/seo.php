<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Configuration
    |--------------------------------------------------------------------------
    |
    | These are the default SEO settings that will be used across the site
    | when specific page settings are not provided.
    |
    */

    'defaults' => [
        'title' => env('APP_NAME', 'Tertab') . ' - Best Reference Platform',
        'description' => 'Get verified academic and professional references from trusted lecturers. Secure, fast, and reliable reference platform for students and professionals.',
        'keywords' => 'academic references, professional references, verified lecturers, student references, academic recommendation letters, university references, college references',
        'author' => env('APP_NAME', 'Tertab'),
        'robots' => 'index, follow',
        'og_image' => '/images/og-image.jpg',
        'twitter_card' => 'summary_large_image',
    ],

    /*
    |--------------------------------------------------------------------------
    | Page-specific SEO Settings
    |--------------------------------------------------------------------------
    |
    | Define specific SEO settings for different pages/routes
    |
    */

    'pages' => [
        'home' => [
            'title' => env('APP_NAME', 'Tertab') . ' - Best Academic Reference Platform | Verified Lecturers',
            'description' => 'Get verified academic and professional references from trusted lecturers worldwide. Secure, fast, and reliable reference platform for students and professionals. Join thousands of satisfied users.',
            'keywords' => 'academic references, professional references, verified lecturers, student references, academic recommendation letters, university references, college references, tertab, reference platform, lecturer verification',
        ],

        'login' => [
            'title' => 'Login - ' . env('APP_NAME', 'Tertab') . ' | Access Your Account',
            'description' => 'Login to your Tertab account to access verified academic and professional references from trusted lecturers. Secure login for students and professionals.',
            'keywords' => 'tertab login, sign in, academic references login, professional references access, student login, lecturer login',
        ],

        'register' => [
            'title' => 'Register - ' . env('APP_NAME', 'Tertab') . ' | Join Our Reference Platform',
            'description' => 'Create your Tertab account to get verified academic and professional references from trusted lecturers. Free registration for students and professionals.',
            'keywords' => 'tertab register, sign up, create account, academic references registration, professional references signup, student registration, lecturer registration',
        ],

        'affiliate' => [
            'title' => 'Become an Affiliate - ' . env('APP_NAME', 'Tertab') . ' | Earn Commission with Reference Platform',
            'description' => 'Join Tertab\'s affiliate program and earn commission by promoting our verified academic reference platform. Great earning potential for educators and influencers.',
            'keywords' => 'tertab affiliate, affiliate program, earn commission, reference platform affiliate, academic affiliate program, education affiliate, lecturer affiliate',
        ],

        'dashboard' => [
            'title' => 'Dashboard - ' . env('APP_NAME', 'Tertab') . ' | Manage Your References',
            'description' => 'Access your Tertab dashboard to manage academic and professional references, track requests, and communicate with verified lecturers.',
            'keywords' => 'tertab dashboard, reference management, academic references dashboard, professional references portal',
            'robots' => 'noindex, nofollow',
        ],

        'admin' => [
            'title' => 'Admin Dashboard - ' . env('APP_NAME', 'Tertab'),
            'description' => 'Admin dashboard for managing references, users, and platform settings.',
            'robots' => 'noindex, nofollow',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Links
    |--------------------------------------------------------------------------
    |
    | Social media profiles for structured data
    |
    */

    'social' => [
        'facebook' => 'https://facebook.com/tertab',
        'twitter' => 'https://twitter.com/tertab',
        'linkedin' => 'https://linkedin.com/company/tertab',
        'instagram' => 'https://instagram.com/tertab',
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization Information
    |--------------------------------------------------------------------------
    |
    | Information about the organization for structured data
    |
    */

    'organization' => [
        'name' => env('APP_NAME', 'Tertab'),
        'description' => 'Leading platform for verified academic and professional references from trusted lecturers.',
        'logo' => '/images/logoimg.png',
        'contact_type' => 'customer service',
        'available_language' => 'English',
    ],
];
