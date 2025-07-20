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

        <!-- External Libraries -->
        <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/management.css') }}">
        
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            .sidebar-link {
                @apply flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200;
            }
            .sidebar-link:hover {
                @apply bg-gray-800 text-white transform translate-x-1;
            }
            .sidebar-link.active {
                @apply bg-blue-600 text-white shadow-lg;
            }
            .sidebar-section {
                @apply mb-6;
            }
            .sidebar-section-title {
                @apply px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" 
                 :class="{ 
                     'translate-x-0': sidebarOpen, 
                     '-translate-x-full': !sidebarOpen,
                     'w-64': !sidebarCollapsed,
                     'w-16': sidebarCollapsed 
                 }"
                 x-init="sidebarCollapsed = window.innerWidth < 1024">
                
                <div class="flex flex-col h-full bg-gray-900 shadow-xl">
                    <!-- Sidebar Header -->
                    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-700">
                        <div class="flex items-center" x-show="!sidebarCollapsed">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-bold text-white">MineCraft</h1>
                                <p class="text-xs text-gray-400">Admin Panel</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <!-- Collapse Button (Desktop) -->
                            <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                    class="hidden lg:block p-1.5 text-gray-400 hover:text-white rounded-md hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          :d="sidebarCollapsed ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7'"></path>
                                </svg>
                            </button>
                            
                            <!-- Close Button (Mobile) -->
                            <button @click="sidebarOpen = false" 
                                    class="lg:hidden p-1.5 text-gray-400 hover:text-white rounded-md hover:bg-gray-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar Navigation -->
                    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-300' }}"
                           :title="sidebarCollapsed ? 'Dashboard' : ''">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v3H8V5z"></path>
                            </svg>
                            <span x-show="!sidebarCollapsed">Dashboard</span>
                        </a>

                        <!-- Business Management Section -->
                        <div class="sidebar-section">
                            <div class="sidebar-section-title" x-show="!sidebarCollapsed">
                                Business Management
                            </div>
                            
                            <a href="{{ route('admin.manage.plans') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.manage.plans') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Plans Management' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Plans</span>
                            </a>

                            <a href="{{ route('admin.manage.orders') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.manage.orders') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Orders Management' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Orders</span>
                            </a>

                            <a href="{{ route('admin.manage.servers') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.manage.servers') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Server Management' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Servers</span>
                            </a>

                            <a href="{{ route('admin.manage.billing') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.manage.billing') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Billing & Invoices' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Billing</span>
                            </a>
                        </div>

                        <!-- User Management Section -->
                        <div class="sidebar-section">
                            <div class="sidebar-section-title" x-show="!sidebarCollapsed">
                                User Management
                            </div>
                            
                            <a href="{{ route('admin.users') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'User Administration' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Users</span>
                            </a>

                            <a href="{{ route('admin.manage.users') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.manage.users') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'User Management' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Management</span>
                            </a>
                        </div>

                        <!-- Communication Section -->
                        <div class="sidebar-section">
                            <div class="sidebar-section-title" x-show="!sidebarCollapsed">
                                Communication
                            </div>
                            
                            <a href="{{ route('admin.notifications.overview') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.notifications.overview') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Notifications Overview' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Overview</span>
                            </a>

                            <a href="{{ route('admin.notifications.index') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.notifications.index') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'My Notifications' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">My Notifications</span>
                            </a>

                            <a href="{{ route('admin.notifications.global') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.notifications.global') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Global Notifications' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Global</span>
                            </a>

                            <a href="{{ route('admin.notifications.create') }}" 
                               class="sidebar-link {{ request()->routeIs('admin.notifications.create') ? 'active' : 'text-gray-300' }}"
                               :title="sidebarCollapsed ? 'Send Notification' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Send</span>
                            </a>
                        </div>

                        <!-- System Section -->
                        <div class="sidebar-section">
                            <div class="sidebar-section-title" x-show="!sidebarCollapsed">
                                System
                            </div>
                            
                            <a href="#" 
                               class="sidebar-link text-gray-300"
                               :title="sidebarCollapsed ? 'System Settings' : ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span x-show="!sidebarCollapsed">Settings</span>
                            </a>
                        </div>
                    </nav>

                    <!-- Sidebar Footer -->
                    <div class="p-4 border-t border-gray-700">
                        <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center' : ''">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium flex-shrink-0"
                                 :class="sidebarCollapsed ? '' : 'mr-3'">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0" x-show="!sidebarCollapsed">
                                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">Administrator</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col transition-all duration-300"
                 :class="sidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64'">
                
                <!-- Top Header Bar -->
                <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <!-- Mobile Sidebar Toggle -->
                                <button @click="sidebarOpen = !sidebarOpen" 
                                        class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md focus:outline-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                </button>

                                <!-- Page Title -->
                                <div class="ml-4 lg:ml-0">
                                    <h1 class="text-xl font-semibold text-gray-900">
                                        @yield('title', 'Admin Dashboard')
                                    </h1>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @yield('subtitle', 'Manage your Minecraft hosting platform')
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Quick Actions -->
                                <div class="flex items-center space-x-2">
                                    <!-- Main Site Link -->
                                    <a href="{{ route('dashboard') }}" 
                                       class="p-2 text-gray-500 hover:text-gray-700 rounded-md hover:bg-gray-100 transition-colors" 
                                       title="Main Site">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </a>

                                    <!-- Notifications -->
                                    <div class="relative">
                                        <button class="p-2 text-gray-500 hover:text-gray-700 rounded-md hover:bg-gray-100 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- User Menu -->
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-2">
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
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden">
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
        
        @stack('scripts')
    </body>
</html>
