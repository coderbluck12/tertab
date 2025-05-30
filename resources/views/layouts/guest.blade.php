<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tertab') }} - Best Reference Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- reCAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('images/logoimg.png') }}" class="w-10 md:w-14">
                <img src="{{ asset('images/logotext.png') }}" class="w-16 md:w-24">
            </div>
            <div class="hidden md:flex space-x-4">
                <a href="/" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4  py-2 md:px-6">Home</a>
                <a href="#about" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4  py-2 md:px-6">About</a>
                <a href="#services" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4  py-2 md:px-6">Services</a>
                <a href="#" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4  py-2 md:px-6">Contact</a>
                <a href="{{ route('login') }}" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4  py-2 md:px-6">Login</a>
                <a href="{{ route('register') }}" class="ml-4 bg-gray-800 text-white px-4 py-2 rounded-full font-semibold hover:bg-blue-800">Get Started</a>
            </div>
            <!-- Hamburger Menu for Mobile -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden px-4 pb-4">
            <a href="#" class="block text-gray-800 font-semibold py-2">Home</a>
            <a href="#about" class="block text-gray-800 font-semibold py-2">About</a>
            <a href="#services" class="block text-gray-800 font-semibold py-2">Services</a>
            <a href="#" class="block text-gray-800 font-semibold py-2">Contact</a>
            <a href="{{ route('login') }}" class="block text-gray-800 font-semibold py-2">Login</a>
            <a href="{{ route('register') }}" class="block bg-gray-800 text-white px-4 py-2 rounded-full font-semibold text-center">Get Started</a>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100">
        <div class="py-4"></div>
        <div>
            <a href="/" class="flex justify-between">
                <x-application-logo src="{{ asset('images/logoimg.png') }}" alt="Application Logo" class="w-10 h-10 fill-current text-gray-500" />
                <x-application-logo src="{{ asset('images/logotext.png') }}" alt="Application Logo" class="h-10 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    </body>
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</html>
