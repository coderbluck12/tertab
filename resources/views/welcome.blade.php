<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tertab') }} - Best Reference Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }

    </style>
    <style>

        /********** ANIMATION STYLES ************/
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .delay-100 {
            animation-delay: 0.1s;
        }
        .delay-200 {
            animation-delay: 0.2s;
        }
        .delay-1000 {
            animation-delay: 1s;
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
        <div class="hidden md:flex space-x-4">
            <a href="#" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4 py-2 md:px-6">Home</a>
            <a href="#about" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4 py-2 md:px-6">About</a>
            <a href="#services" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4 py-2 md:px-6">Services</a>
            <a href="#" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4 py-2 md:px-6">Contact</a>
            <!-- Check if user is logged in -->
            @auth
                <a href="{{ route('dashboard') }}" class="ml-4 bg-gray-800 text-white px-4 py-2 rounded-full font-semibold hover:bg-blue-800">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-800 font-semibold hover:text-gray-800 hover:border hover:border-gray-800 hover:rounded-xl hover:py-1 hover:px-3 px-4 py-2 md:px-6">Login</a>
                <a href="{{ route('register') }}" class="ml-4 bg-gray-800 text-white px-4 py-2 rounded-full font-semibold hover:bg-blue-800">Get Started</a>
            @endauth
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
        <!-- Check if user is logged in -->
        @auth
            <a href="{{ route('dashboard') }}" class="block bg-gray-800 text-white px-4 py-2 rounded-full font-semibold text-center">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="block text-gray-800 font-semibold py-2">Login</a>
            <a href="{{ route('register') }}" class="block bg-gray-800 text-white px-4 py-2 rounded-full font-semibold text-center">Get Started</a>
        @endauth
    </div>
</nav>

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16 md:py-32 overflow-hidden">
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-95"></div>
    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-6xl font-bold leading-tight animate-fade-in-up">Secure Professional References from Verified Lecturers</h1>
        <p class="mt-6 text-lg md:text-xl text-gray-200 animate-fade-in-up delay-100">Get the academic and professional references you need, verified and trusted by top lecturers worldwide.</p>
        <!-- Buttons -->
        <div class="mt-8 space-y-4 md:space-y-0 md:space-x-4 animate-fade-in-up delay-200">
            <a href="/register" class="inline-block bg-white text-blue-600 px-6 py-3 md:px-8 md:py-4 rounded-full font-semibold text-lg shadow-lg hover:bg-gray-100 hover-scale">
                Get Started Now
            </a>
            <a href="/login" class="inline-block bg-white text-blue-600 px-6 py-3 md:px-8 md:py-4 rounded-full font-semibold text-lg shadow-lg hover:bg-gray-100 hover-scale">
                Login
            </a>
        </div>
    </div>
    <!-- Animated Circles -->
    <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full opacity-30 animate-float"></div>
    <div class="absolute -top-32 -right-32 w-64 h-64 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full opacity-30 animate-float delay-1000"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full opacity-30 animate-float"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full opacity-30 animate-float delay-1000"></div>
</section>

<!-- How It Works -->
<section class="py-12 md:py-20 bg-white" id="about">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold">How It Works</h2>
        <p class="mt-4 text-gray-600 text-lg md:text-xl">A simple, secure, and efficient process to get your references.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8 md:mt-12">
            <div class="p-6 md:p-8 bg-gray-50 rounded-xl shadow-lg hover-scale">
                <div class="text-4xl font-bold text-blue-600">1</div>
                <h3 class="text-xl md:text-2xl font-semibold mt-6">Request a Reference</h3>
                <p class="mt-4 text-gray-600">Submit a request for a reference and choose a verified lecturer.</p>
            </div>
            <div class="p-6 md:p-8 bg-gray-50 rounded-xl shadow-lg hover-scale">
                <div class="text-4xl font-bold text-blue-600">2</div>
                <h3 class="text-xl md:text-2xl font-semibold mt-6">Lecturer Reviews & Approves</h3>
                <p class="mt-4 text-gray-600">The lecturer reviews your request and provides a document or email reference.</p>
            </div>
            <div class="p-6 md:p-8 bg-gray-50 rounded-xl shadow-lg hover-scale">
                <div class="text-4xl font-bold text-blue-600">3</div>
                <h3 class="text-xl md:text-2xl font-semibold mt-6">Payment & Confirmation</h3>
                <p class="mt-4 text-gray-600">Payment is securely processed via escrow upon completion.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features & Benefits -->
<section class="py-12 md:py-20 bg-gray-50" id="services">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold">Why Choose Our Platform?</h2>
        <p class="mt-4 text-gray-600 text-lg md:text-xl">We make it easy, secure, and reliable to get the references you need.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-8 md:mt-12">
            <div class="p-6 md:p-8 bg-white rounded-xl shadow-lg hover-scale">
                <div class="text-3xl font-bold text-blue-600">✅</div>
                <h3 class="text-xl font-semibold mt-6">Verified Lecturers</h3>
                <p class="mt-4 text-gray-600">All lecturers are rigorously verified for authenticity.</p>
            </div>
            <div class="p-6 md:p-8 bg-white rounded-xl shadow-lg hover-scale">
                <div class="text-3xl font-bold text-blue-600">💳</div>
                <h3 class="text-xl font-semibold mt-6">Secure Escrow Payment</h3>
                <p class="mt-4 text-gray-600">Payments are held securely and released only upon completion.</p>
            </div>
            <div class="p-6 md:p-8 bg-white rounded-xl shadow-lg hover-scale">
                <div class="text-3xl font-bold text-blue-600">📄</div>
                <h3 class="text-xl font-semibold mt-6">Multiple Reference Types</h3>
                <p class="mt-4 text-gray-600">Choose between signed documents or email-based references.</p>
            </div>
            <div class="p-6 md:p-8 bg-white rounded-xl shadow-lg hover-scale">
                <div class="text-3xl font-bold text-blue-600">🔔</div>
                <h3 class="text-xl font-semibold mt-6">Instant Notifications</h3>
                <p class="mt-4 text-gray-600">Stay updated with real-time alerts on your reference status.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold">What Our Users Say</h2>
        <p class="mt-4 text-gray-600 text-lg md:text-xl">Hear from students and lecturers who trust our platform.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8 md:mt-12">
            <div class="p-6 md:p-8 bg-gray-50 rounded-xl shadow-lg hover-scale">
                <p class="text-gray-600 italic">"This platform made it so easy to get a verified reference. Highly recommended!"</p>
                <div class="mt-6 font-semibold">— Mary Akinfenwa.</div>
            </div>
            <div class="p-6 md:p-8 bg-gray-50 rounded-xl shadow-lg hover-scale">
                <p class="text-gray-600 italic">"As a lecturer, I appreciate the secure and efficient process. Great platform!"</p>
                <div class="mt-6 font-semibold">— Dr. Olanipekun Excel.</div>
            </div>
            <div class="p-6 md:p-8 bg-gray-50 rounded-xl shadow-lg hover-scale">
                <p class="text-gray-600 italic">"The escrow payment system gives me peace of mind. Very professional!"</p>
                <div class="mt-6 font-semibold">— Adelakun Jnr.</div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-16 md:py-24 overflow-hidden">
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-95"></div>
    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold animate-fade-in-up">Ready to Secure Your Reference?</h2>
        <p class="mt-4 text-lg md:text-xl text-gray-200 animate-fade-in-up delay-100">Join thousands of students and lecturers benefiting from our trusted platform.</p>
        <a href="/register" class="mt-8 inline-block bg-white text-blue-600 px-6 py-3 md:px-8 md:py-4 rounded-full font-semibold text-lg shadow-lg hover:bg-gray-100 hover-scale animate-fade-in-up delay-200">
            Register Now
        </a>
    </div>
</section>

<!-- JavaScript for Mobile Menu -->
<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>

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

</body>
</html>
