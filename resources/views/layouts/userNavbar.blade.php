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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center">

                    <!-- LEFT: BRAND -->
                    <div class="flex-1">
                        <a href="{{ route('user.dashboard') }}" class="text-2xl font-bold tracking-tight">
                            🛍️ EduWork Shop
                        </a>
                    </div>

                    <!-- CENTER: MENU -->
                    <div class="hidden md:flex gap-10 text-sm items-center">
                        <a href="{{ route('user.dashboard') }}"
                        class="{{ request()->routeIs('user.dashboard') ? 'underline font-medium text-black' : 'text-gray-600 hover:text-black' }}">
                            Home
                        </a>

                        <!-- SHOP  -->
                        <a href="{{ route('user.products.index') }}"
                        class="{{ request()->routeIs('user.products.*') ? 'underline font-medium text-black' : 'text-gray-600 hover:text-black' }}">
                            Shop
                        </a>

                        <a href="#" class="text-gray-600 hover:text-black">Contact</a>
                        <a href="#" class="text-gray-600 hover:text-black">About</a>

                        @guest
                            <a href="{{ route('register') }}" class="text-gray-600 hover:text-black">Sign Up</a>
                        @endguest
                    </div>

                    <!-- RIGHT: SEARCH + ICONS -->
                    <div class="flex-1 flex justify-end items-center gap-6 ml-10">

                        <!-- SEARCH BAR -->
                        <div class="hidden md:flex items-center bg-gray-100 rounded-md pl-4 pr-3 py-2 w-64">
                            <form method="GET" action="{{ route('user.products.index') }}" class="flex items-center w-full">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="What are you looking for?"
                                    class="bg-gray-100 w-full text-sm outline-none"
                                >
                                <button type="submit" class="ml-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-4.35-4.35M16 10a6 6 0 11-12 0 6 6 0 0112 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- HEART ICON -->
                        <button aria-label="Wishlist">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </button>

                        <!-- CART ICON -->
                        <a href="{{ route('user.cart.index') }}" class="relative" aria-label="Cart">
                            <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9M17 13l2 9M6 22a1 1 0 100-2 1 1 0 000 2zm12 0a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>

                            @auth
                            <span id="cart-badge"
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                                {{ auth()->user()->cartItems()->count() }}
                            </span>
                            @endauth
                        </a>

                        <!-- AUTH DROPDOWN / LOGIN LINKS -->
                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 text-sm text-gray-700">
                                        <div>{{ auth()->user()->name }}</div>
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <!-- keep admin/user profile logic -->
                                    @if(auth()->user() && auth()->user()->is_admin)
                                        <x-dropdown-link :href="route('admin.profile.edit')">Profile</x-dropdown-link>
                                    @else
                                        <x-dropdown-link :href="route('user.profile.edit')">Profile</x-dropdown-link>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link
                                            :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                        >Log Out</x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <div class="hidden md:flex items-center gap-4">
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Register</a>
                                @endif
                            </div>
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
                @include('layouts.footer')
            </main>
        </div>
    </body>
</html>

<script>
// Update cart badge count
document.addEventListener('DOMContentLoaded', function() {
    @auth
        fetch('{{ route("user.cart.count") }}')
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
