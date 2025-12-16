<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo / Brand -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('user.dashboard') }}" class="font-bold text-xl text-blue-600">🛍️ EduWork
                                Shop</a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <a href="{{ route('user.products.index') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('user.products.*') ? 'border-blue-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                {{ __('Shop') }}
                            </a>
                            @auth
                                <a href="{{ route('user.cart.index') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('user.cart.*') ? 'border-blue-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    {{ __('Cart') }} <span
                                        class="ml-1 bg-red-500 text-white text-xs px-2 py-1 rounded-full"
                                        id="cart-badge">{{ auth()->user()->cartItems()->count() }}</span>
                                </a>
                                <a href="{{ route('user.home') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2">Home</a>
                            @endauth
                        </div>
                    </div>


                    <!-- Right Menu -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ auth()->user()->name }}</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @if (auth()->user() && auth()->user()->is_admin)
                                        <x-dropdown-link :href="route('admin.profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>
                                    @else
                                        <x-dropdown-link :href="route('user.profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>
                                    @endif

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <div class="space-x-4">
                                <a href="{{ route('login') }}"
                                    class="text-sm text-gray-700 hover:text-gray-900">{{ __('Log in') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">{{ __('Register') }}</a>
                                @endif
                            </div>
                        @endauth
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('user.products.index')" :active="request()->routeIs('user.products.*')">
                        {{ __('Shop') }}
                    </x-responsive-nav-link>
                    @auth
                        <x-responsive-nav-link :href="route('user.cart.index')" :active="request()->routeIs('user.cart.*')">
                            {{ __('Cart') }}
                        </x-responsive-nav-link>
                    @endauth
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        @auth
                            <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                        @else
                            <div class="font-medium text-base text-gray-800">{{ __('Guest User') }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ __('Browse and shop') }}</div>
                        @endauth
                    </div>

                    <div class="mt-3 space-y-1">
                        @auth
                            <x-responsive-nav-link :href="route('user.dashboard')">
                                {{ __('Dashboard') }}
                            </x-responsive-nav-link>
                            @if (auth()->user() && auth()->user()->is_admin)
                                <x-responsive-nav-link :href="route('admin.profile.edit')">
                                    {{ __('Profile') }}
                                </x-responsive-nav-link>
                            @else
                                <x-responsive-nav-link :href="route('user.profile.edit')">
                                    {{ __('Profile') }}
                                </x-responsive-nav-link>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        @else
                            <x-responsive-nav-link :href="route('login')">
                                {{ __('Log in') }}
                            </x-responsive-nav-link>
                            @if (Route::has('register'))
                                <x-responsive-nav-link :href="route('register')">
                                    {{ __('Register') }}
                                </x-responsive-nav-link>
                            @endif
                        @endauth
                    </div>
                </div>
        </nav>

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-2">
            <x-flash-messages />
        </div>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>

<script>
    // Update cart badge count
    document.addEventListener('DOMContentLoaded', function() {
        @auth
        fetch('{{ route('user.cart.count') }}')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cart-badge');
                if (cartBadge && data && typeof data.count !== 'undefined') {
                    cartBadge.textContent = data.count;
                }
            })
            .catch(e => console.log('Cart badge error:', e));
    @endauth
    });
</script>
