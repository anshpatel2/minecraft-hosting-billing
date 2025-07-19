@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- FontAwesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Admin Theme CSS -->
        <link href="{{ asset('assets/css/admin-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 admin-content">
            <!-- Admin Navigation -->
            <nav class="admin-header bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="flex-shrink-0">
                                <h1 class="text-xl font-bold text-gray-900">
                                    <span class="text-blue-600">Minecraft</span> Hosting Admin
                                </h1>
                            </div>

                            <!-- Admin Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')">
                                    {{ __('Users') }}
                                </x-nav-link>
                                <x-nav-link href="#" :active="false">
                                    {{ __('Servers') }}
                                </x-nav-link>
                                <x-nav-link href="#" :active="false">
                                    {{ __('Billing') }}
                                </x-nav-link>
                                <x-nav-link href="#" :active="false">
                                    {{ __('Settings') }}
                                </x-nav-link>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Quick Actions -->
                            <div class="flex items-center space-x-4 mr-4">
                                <!-- Theme Toggle -->
                                <button id="theme-toggle" class="theme-toggle" title="Toggle Dark Mode">
                                    <svg class="sun-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <svg class="moon-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                </button>

                                <!-- Main Site Link -->
                                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors" title="Main Site">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </a>

                                <!-- Notifications -->
                                <div class="relative">
                                    <button id="notification-btn" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition-colors" title="Notifications">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                                        </svg>
                                        <span id="notification-badge" class="notification-badge">3</span>
                                    </button>
                                    
                                    <!-- Notification Dropdown -->
                                    <div id="notification-dropdown" class="notification-dropdown">
                                        <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                                                <button onclick="adminTheme.markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                    Mark all read
                                                </button>
                                            </div>
                                        </div>
                                        <div id="notification-list" class="max-h-96 overflow-y-auto">
                                            <!-- Notifications will be loaded here -->
                                        </div>
                                        <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View all notifications</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-2">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                            <div class="text-left">
                                                <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                                                <div class="font-medium text-xs text-gray-500">Administrator</div>
                                            </div>
                                        </div>
                                        <div class="ml-1">
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
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
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
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users*')">
                            {{ __('Users') }}
                        </x-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>

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
                </div>
            </nav>

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
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin-datatables.js') }}"></script>
        <script src="{{ asset('assets/js/admin-theme.js') }}"></script>
        
        <!-- Page specific scripts -->
        @stack('scripts')
    </body>
</html>
