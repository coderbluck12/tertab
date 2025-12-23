<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex justify-between items-center">
                    <a href="{{ route('dashboard') }}" class="flex justify-between">
                        <x-application-logo :src="asset('images/logoimg.png')" class="w-10 h-10 fill-current text-gray-500" />
                        <x-application-logo :src="asset('images/logotext.png')" class="h-10 fill-current text-gray-500" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('request-for-reference')
                        <x-nav-link :href="route('wallet.show')" :active="request()->routeIs('wallet.show')" class="font-bold">
                            {{ __('Wallet') }}
                        </x-nav-link>
                        <x-nav-link :href="route('referrals.index')" :active="request()->routeIs('referrals.index')" class="font-bold">
                            {{ __('Referrals') }}
                        </x-nav-link>
                    @endcan

                    @can('provide-a-reference')
                        <x-nav-link :href="route('wallet.show')" :active="request()->routeIs('wallet.show')" class="font-bold">
                            {{ __('Wallet') }}
                        </x-nav-link>
                        <x-nav-link :href="route('referrals.index')" :active="request()->routeIs('referrals.index')" class="font-bold">
                            {{ __('Referrals') }}
                        </x-nav-link>
                    @endcan

                    @can('request-for-reference')
                        @if(auth()->check() && auth()->user()->status === 'verified')
                            <x-nav-link :href="route('institution.attended.create')" :active="request()->routeIs('institution.attended.create')" class="font-bold">
                                {{ auth()->user()->attended()->count() > 0 ? 'Add more Institution' : 'Add an Institution' }}
                            </x-nav-link>
                            <x-nav-link :href="route('student.reference')" :active="request()->routeIs('student.reference')" class="font-bold">
                                Request for Reference
                            </x-nav-link>
                        @endif
                    @endcan

                    @can('provide-a-reference')
{{--                        <x-nav-link :href="route('lecturer.create')" :active="request()->routeIs('lecturer.create')">--}}
{{--                            References--}}
{{--                        </x-nav-link>--}}
{{--                        <x-nav-link :href="route('lecturer.index')" :active="request()->routeIs('lecturer.index')">--}}
{{--                            Approved References--}}
{{--                        </x-nav-link>--}}
                        @if(auth()->check() && auth()->user()->status === 'verified')
                            <x-nav-link :href="route('institution.attended.create')" :active="request()->routeIs('institution.attended.show')" class="font-bold">
                            {{ auth()->user()->attended()->count() > 0 ? 'Add more Institution' : 'Add an Institution' }}
                            </x-nav-link>
                        @endif
                    @endcan

                    @can('manage-platform')
                        <x-nav-link :href="route('admin.platform.settings')" :active="request()->routeIs('admin.platform.settings')" class="font-bold">
                            Configure Platform
                        </x-nav-link>
                        <x-nav-link :href="route('admin.lecturers')" :active="request()->routeIs('admin.lecturers')" class="font-bold">
                            Lecturers
                        </x-nav-link>

                        <x-nav-link :href="route('admin.students')" :active="request()->routeIs('admin.students')" class="font-bold">
                            Students
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users')" class="font-bold">
                            Users
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notification Bell -->
                <div class="mr-4">
                    <x-notification-bell />
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="/">
                                {{ __('Home') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can('request-for-reference')
                <x-responsive-nav-link :href="route('wallet.show')" :active="request()->routeIs('wallet.show')" class="font-bold">
                    {{ __('Wallet') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('referrals.index')" :active="request()->routeIs('referrals.index')" class="font-bold">
                    {{ __('Referrals') }}
                </x-responsive-nav-link>
            @endcan
            
            @can('provide-a-reference')
                <x-responsive-nav-link :href="route('wallet.show')" :active="request()->routeIs('wallet.show')" class="font-bold">
                    {{ __('Wallet') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('referrals.index')" :active="request()->routeIs('referrals.index')" class="font-bold">
                    {{ __('Referrals') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @can('request-for-reference')
                    @if(auth()->check() && auth()->user()->status === 'verified')
                        <x-responsive-nav-link :href="route('student.reference')" :active="request()->routeIs('student.reference')" class="font-bold">
                            Request for Reference
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('institution.attended.show')" :active="request()->routeIs('institution.attended.show')" class="font-bold">
                            {{ auth()->user()->attended()->count() > 0 ? 'Add more Institution' : 'Add an Institution' }}
                        </x-responsive-nav-link>
                    @endif
                @endcan

                @can('provide-a-reference')
                    @if(auth()->check() && auth()->user()->status === 'verified')
                        <x-responsive-nav-link :href="route('institution.attended.show')" :active="request()->routeIs('institution.attended.show')" class="font-bold">
                            Add Institution Teaching At
                        </x-responsive-nav-link>
                    @endif
                @endcan

                @can('manage-platform')
                    <x-responsive-nav-link :href="route('admin.platform.settings')" :active="request()->routeIs('admin.platform.settings')" class="font-bold">
                        Configure Platform
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.lecturers')" :active="request()->routeIs('admin.lecturers')" class="font-bold">
                        Lecturers
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('admin.students')" :active="request()->routeIs('admin.students')" class="font-bold">
                        Students
                    </x-responsive-nav-link>
                @endcan

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
