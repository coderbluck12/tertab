<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>@yield('title', config('app.name', 'Tertab') . ' - Best Reference Platform')</title>
        <meta name="description" content="@yield('description', 'Get verified academic and professional references from trusted lecturers. Secure, fast, and reliable reference platform for students and professionals.')">
        <meta name="keywords" content="@yield('keywords', 'references, academic references, word references, professional references, verified lecturers, student references, academic recommendation letters, university references, college references')">
        <meta name="author" content="{{ config('app.name', 'Tertab') }}">
        <meta name="robots" content="@yield('robots', 'index, follow')">
        <link rel="canonical" href="@yield('canonical', request()->url())">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="@yield('og_title', config('app.name', 'Tertab') . ' - Best Reference Platform')">
        <meta property="og:description" content="@yield('og_description', 'Get verified academic and professional references from trusted lecturers. Secure, fast, and reliable reference platform.')">
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="@yield('og_url', request()->url())">
        <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
        <meta property="og:site_name" content="{{ config('app.name', 'Tertab') }}">
        <meta property="og:locale" content="@yield('og_locale', 'en_US')">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
        <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'Tertab') . ' - Best Reference Platform')">
        <meta name="twitter:description" content="@yield('twitter_description', 'Get verified academic and professional references from trusted lecturers.')">
        <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-image.jpg'))">

        <!-- Additional SEO Meta Tags -->
        <meta name="theme-color" content="#2563eb">
        <meta name="msapplication-TileColor" content="#2563eb">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Include Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>

        <!-- reCAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @auth
            <x-verification-toast :user="auth()->user()" />
        @endauth
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Tawk.to Live Chat -->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/68b5cd2b862a55192487ab18/1j432iu39';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
<!--End of Tawk.to Script-->
    </body>
</html>
