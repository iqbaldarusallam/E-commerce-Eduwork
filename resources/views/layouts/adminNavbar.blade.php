<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Bootstrap Icons CDN -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @if (!request()->routeIs('admin.*'))
                @include('layouts.navigation')
            @else

                <div class="min-h-screen flex">
                    <aside class="w-64 bg-white text-gray-900 border-r border-gray-200 hidden md:flex flex-col">
                        <div class="px-2 py-3 flex items-center justify-center border-b border-gray-200">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                                <img src="{{ asset('images/Logo.png') }}" alt="edushop" class="h-10 w-20 object-contain">
                            </a>
                            <button id="sidebarToggle" class="md:hidden text-gray-900 absolute right-4">✕</button>
                        </div>

                        <nav class="flex-1 px-4 py-6 overflow-y-auto">
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-100 transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        <i class="bi bi-speedometer2"></i>
                                        <span class="ml-1">Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-100 transition {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        <i class="bi bi-receipt"></i>
                                        <span class="ml-1">Orders</span>
                                        <span class="ml-auto bg-indigo-600 px-2 py-0.5 rounded text-xs text-white">{{ \App\Models\Transaction::count() }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-100 transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        <i class="bi bi-box-seam"></i>
                                        <span class="ml-1">Products</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-100 transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        <i class="bi bi-tags"></i>
                                        <span class="ml-1">Categories</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-100 transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        <i class="bi bi-people"></i>
                                        <span class="ml-1">Customers</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-100 transition {{ request()->routeIs('admin.profile.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                                        <i class="bi bi-person"></i>
                                        <span class="ml-1">Edit Profile</span>
                                    </a>
                                </li>
                                <li class="pt-2 border-t border-gray-200">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 transition text-white text-sm">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span class="ml-1">Logout</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </nav>
                    </aside>

                    <div class="flex-1 bg-gray-50 min-h-screen">
                        <header class="bg-white border-b">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                                <div class="flex items-center gap-4">
                                    <button id="openSidebar" class="md:hidden px-2 py-1 rounded bg-gray-100">☰</button>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="text-sm text-gray-600">Hi, {{ auth()->user()->name ?? '' }}</div>
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=2563eb&color=fff" class="h-8 w-8 rounded-full">
                                </div>
                            </div>
                        </header>
            @endif

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
                <div class="w-full py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <x-flash-messages />
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
        <script>
            // Sidebar toggle for small screens
            (function(){
                const openBtn = document.getElementById('openSidebar');
                const sidebar = document.querySelector('aside.w-64');
                const closeBtn = document.getElementById('sidebarToggle');

                function toggleSidebar() {
                    if (!sidebar) return;
                    sidebar.classList.toggle('hidden');
                }

                if (openBtn) openBtn.addEventListener('click', toggleSidebar);
                if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            })();
        </script>
        </body>
    </html>
