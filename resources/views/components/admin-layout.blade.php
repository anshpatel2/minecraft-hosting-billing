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
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Admin Theme CSS -->
        <link href="{{ asset('assets/css/admin-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet">
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 admin-content" x-data="{ open: false }">
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
                                <x-nav-link :href="route('admin.manage.plans')" :active="request()->routeIs('admin.manage.plans')">
                                    {{ __('Plans') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.manage.orders')" :active="request()->routeIs('admin.manage.orders')">
                                    {{ __('Orders') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.manage.servers')" :active="request()->routeIs('admin.manage.servers')">
                                    {{ __('Servers') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.manage.billing')" :active="request()->routeIs('admin.manage.billing')">
                                    {{ __('Billing') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.manage.users')" :active="request()->routeIs('admin.manage.users')">
                                    {{ __('User Management') }}
                                </x-nav-link>

                                <!-- Notifications Dropdown -->
                                <div class="relative ml-3" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                        {{ __('Notifications') }}
                                        <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                        <div class="py-1">
                                            <x-nav-link :href="route('admin.notifications.overview')" :active="request()->routeIs('admin.notifications.overview')">
                                                {{ __('Overview') }}
                                            </x-nav-link>
                                            <x-nav-link :href="route('admin.notifications.index')" :active="request()->routeIs('admin.notifications.index')">
                                                {{ __('My Notifications') }}
                                            </x-nav-link>
                                            <x-nav-link :href="route('admin.notifications.global')" :active="request()->routeIs('admin.notifications.global')">
                                                {{ __('Global Notifications') }}
                                            </x-nav-link>
                                            <x-nav-link :href="route('admin.notifications.create')" :active="request()->routeIs('admin.notifications.create')">
                                                {{ __('Send Notification') }}
                                            </x-nav-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings and User Menu -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>

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
                        <x-responsive-nav-link :href="route('admin.manage.plans')" :active="request()->routeIs('admin.manage.plans')">
                            {{ __('Plans') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.manage.orders')" :active="request()->routeIs('admin.manage.orders')">
                            {{ __('Orders') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.manage.servers')" :active="request()->routeIs('admin.manage.servers')">
                            {{ __('Servers') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.manage.billing')" :active="request()->routeIs('admin.manage.billing')">
                            {{ __('Billing') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.manage.users')" :active="request()->routeIs('admin.manage.users')">
                            {{ __('User Management') }}
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
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

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
