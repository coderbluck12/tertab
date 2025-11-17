<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title>Become an Affiliate - {{ config('app.name', 'Tertab') }} | Earn Commission with Reference Platform</title>
    <meta name="description" content="Join Tertab's affiliate program and earn commission by promoting our verified academic reference platform. Great earning potential for educators and influencers.">
    <meta name="keywords" content="tertab affiliate, affiliate program, earn commission, reference platform affiliate, academic affiliate program, education affiliate, lecturer affiliate">
    <meta name="author" content="{{ config('app.name', 'Tertab') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/affiliate') }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Become an Affiliate - {{ config('app.name', 'Tertab') }}">
    <meta property="og:description" content="Join Tertab's affiliate program and earn commission by promoting our verified academic reference platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/affiliate') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Tertab') }}">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Navigation -->
<nav class="bg-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="{{ asset('images/logoimg.png') }}" class="w-10 md:w-14">
            <img src="{{ asset('images/logotext.png') }}" class="w-16 md:w-24">
        </div>
        <div class="flex space-x-4">
            <a href="/" class="text-gray-800 font-semibold hover:text-blue-600 px-4 py-2">Home</a>
            <a href="{{ route('login') }}" class="text-gray-800 font-semibold hover:text-blue-600 px-4 py-2">Login</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700">Register</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="gradient-bg text-white py-16 md:py-24">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">Become an Affiliate Partner</h1>
        <p class="mt-6 text-lg md:text-xl text-gray-200">Earn commissions by referring students and lecturers to our platform</p>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Why Join Our Affiliate Program?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-gray-50 rounded-xl shadow-lg text-center">
                <div class="text-4xl font-bold text-blue-600 mb-4">💰</div>
                <h3 class="text-xl font-semibold mb-3">Generous Commissions</h3>
                <p class="text-gray-600">Earn competitive commissions on every successful referral</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-xl shadow-lg text-center">
                <div class="text-4xl font-bold text-blue-600 mb-4">📊</div>
                <h3 class="text-xl font-semibold mb-3">Real-time Tracking</h3>
                <p class="text-gray-600">Monitor your referrals and earnings in real-time</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-xl shadow-lg text-center">
                <div class="text-4xl font-bold text-blue-600 mb-4">🎯</div>
                <h3 class="text-xl font-semibold mb-3">Marketing Support</h3>
                <p class="text-gray-600">Get access to promotional materials and support</p>
            </div>
        </div>
    </div>
</section>

<!-- Application Form Section -->
<section class="py-12 md:py-20 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-8">Apply to Become an Affiliate</h2>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('affiliate.store') }}" method="POST" class="bg-white p-8 rounded-xl shadow-lg">
            @csrf
            
            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label for="reason" class="block text-gray-700 font-semibold mb-2">Why do you want to become an affiliate? *</label>
                <textarea id="reason" name="reason" rows="5" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Tell us about your experience, audience, and how you plan to promote our platform (minimum 50 characters)">{{ old('reason') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Minimum 50 characters</p>
            </div>

            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" required class="mt-1 mr-2">
                    <span class="text-sm text-gray-600">I agree to the terms and conditions of the affiliate program</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition">
                Submit Application
            </button>
        </form>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                <h3 class="text-xl font-semibold mb-2">Apply</h3>
                <p class="text-gray-600">Submit your application form</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                <h3 class="text-xl font-semibold mb-2">Get Approved</h3>
                <p class="text-gray-600">We review and approve your application</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                <h3 class="text-xl font-semibold mb-2">Share Your Link</h3>
                <p class="text-gray-600">Get your unique referral link and start promoting</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                <h3 class="text-xl font-semibold mb-2">Earn Commissions</h3>
                <p class="text-gray-600">Get paid for every successful referral</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-12 md:py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Frequently Asked Questions</h2>
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">How much can I earn?</h3>
                <p class="text-gray-600">You earn a commission for every successful referral. The exact amount depends on the type of user and their activity on the platform.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">When do I get paid?</h3>
                <p class="text-gray-600">Commissions are credited to your wallet once your referred user completes their first transaction on the platform.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">Is there a limit to how many people I can refer?</h3>
                <p class="text-gray-600">No! You can refer as many people as you want. The more you refer, the more you earn.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">How long does the approval process take?</h3>
                <p class="text-gray-600">We typically review applications within 2-3 business days. You'll receive an email once your application is processed.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Tertab') }}. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
