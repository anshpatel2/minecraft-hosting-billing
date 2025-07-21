@props(['title' => null])

@php
    // Get unread notifications for the current user
    $unreadNotifications = 0;
    $recentNotifications = collect();
    
    if (auth()->check()) {
        $unreadNotifications = auth()->user()->unreadNotifications()->count();
        $recentNotifications = auth()->user()->notifications()
            ->latest()
            ->limit(5)
            ->get();
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - ' . config('app.name') : config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/responsive-nav.css', 'resources/js/app.js'])
        
        <style>
            /* Custom styles for admin components */
            .admin-card {
                @apply bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6;
            }
            
            .admin-form {
                @apply bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6;
            }
            
            .admin-info-item {
                @apply space-y-2;
            }
            
            .admin-info-label {
                @apply block text-sm font-medium text-gray-700 dark:text-gray-300;
            }
            
            .admin-info-value {
                @apply text-gray-900 dark:text-gray-100;
            }
            
            .admin-btn {
                @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150;
            }
            
            .admin-btn-primary {
                @apply bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25;
            }
            
            .admin-btn-secondary {
                @apply bg-gray-600 text-white hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25;
            }
            
            .admin-alert {
                @apply p-4 rounded-lg;
            }
            
            .admin-alert-warning {
                @apply bg-yellow-50 border border-yellow-200 text-yellow-800;
            }
            
            .admin-quick-action {
                @apply flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-gradient-to-r from-indigo-600 to-purple-700 shadow-lg global-nav">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo and Brand -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <svg class="w-8 h-8 text-white mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-white text-xl font-bold">MineCraft Hosting</span>
                            </div>
                            <p class="text-indigo-200 text-sm ml-4 hidden md:block">Professional Server Management</p>
                        </div>

                        <!-- Desktop Navigation -->
                        <div class="hidden md:flex items-center space-x-8">
                            @if(Auth::check() && Auth::user()->hasRole('admin'))
                                <!-- Admin Navigation -->
                                <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                                <a href="{{ url('/admin/manage/users') }}" class="nav-link {{ request()->is('admin/manage/users') ? 'active' : '' }}">
                                    <i class="fas fa-users mr-2"></i>Users
                                </a>
                                <a href="{{ url('/admin/manage/plans') }}" class="nav-link {{ request()->is('admin/manage/plans') ? 'active' : '' }}">
                                    <i class="fas fa-box mr-2"></i>Plans
                                </a>
                                <a href="{{ url('/admin/manage/orders') }}" class="nav-link {{ request()->is('admin/manage/orders') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart mr-2"></i>Orders
                                </a>
                                <a href="{{ url('/admin/manage/servers') }}" class="nav-link {{ request()->is('admin/manage/servers') ? 'active' : '' }}">
                                    <i class="fas fa-server mr-2"></i>Servers
                                </a>
                                <a href="{{ url('/admin/manage/billing') }}" class="nav-link {{ request()->is('admin/manage/billing') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card mr-2"></i>Billing
                                </a>
                            @elseif(Auth::check() && Auth::user()->hasRole('reseller'))
                                <!-- Reseller Navigation -->
                                <a href="{{ url('/reseller/dashboard') }}" class="nav-link {{ request()->is('reseller/dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                                <a href="{{ url('/reseller/customers') }}" class="nav-link {{ request()->is('reseller/customers') ? 'active' : '' }}">
                                    <i class="fas fa-users mr-2"></i>Customers
                                </a>
                                <a href="{{ url('/reseller/servers') }}" class="nav-link {{ request()->is('reseller/servers') ? 'active' : '' }}">
                                    <i class="fas fa-server mr-2"></i>Servers
                                </a>
                                <a href="{{ url('/reseller/billing') }}" class="nav-link {{ request()->is('reseller/billing') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card mr-2"></i>Billing
                                </a>
                            @else
                                <!-- Customer Navigation -->
                                <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                                <a href="{{ url('/servers') }}" class="nav-link {{ request()->is('servers') ? 'active' : '' }}">
                                    <i class="fas fa-server mr-2"></i>Servers
                                </a>
                                <a href="{{ url('/billing') }}" class="nav-link {{ request()->is('billing') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card mr-2"></i>Billing
                                </a>
                                <a href="{{ url('/support') }}" class="nav-link {{ request()->is('support') ? 'active' : '' }}">
                                    <i class="fas fa-life-ring mr-2"></i>Support
                                </a>
                            @endif
                        </div>

                        <!-- User Menu -->
                        <div class="hidden md:flex items-center space-x-4">
                            @auth
                                <!-- Notifications -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="relative text-white hover:text-indigo-200 transition-colors p-2">
                                        <i class="fas fa-bell text-lg"></i>
                                        @if($unreadNotifications > 0)
                                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                                {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                                            </span>
                                        @endif
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        
                                        <div class="p-4 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                                                @if($unreadNotifications > 0)
                                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                        {{ $unreadNotifications }} new
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="max-h-64 overflow-y-auto">
                                            @forelse($recentNotifications as $notification)
                                                <div class="p-3 border-b border-gray-100 hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-bell text-white text-xs"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 flex-1">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $notification->data['title'] ?? 'Notification' }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $notification->data['message'] ?? 'No message' }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                        @if(!$notification->read_at)
                                                            <div class="flex-shrink-0">
                                                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="p-4 text-center text-gray-500">
                                                    <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                                    <p>No notifications yet</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        
                                        @if($recentNotifications->count() > 0)
                                            <div class="p-3 border-t border-gray-200 bg-gray-50">
                                                <a href="{{ route('admin.notifications.index') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                                    View all notifications
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- User Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="flex items-center text-white hover:text-indigo-200 transition-colors">
                                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-sm font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                        <span class="font-medium">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down ml-2 text-sm"></i>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        
                                        <div class="p-2">
                                            <div class="px-3 py-2 border-b border-gray-100">
                                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                                @if(Auth::user()->roles->first())
                                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full mt-1">
                                                        {{ Auth::user()->roles->first()->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                                <i class="fas fa-user mr-2"></i>Profile
                                            </a>
                                            
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-white hover:text-indigo-200 font-medium">Login</a>
                                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium hover:bg-indigo-50 transition-colors">Register</a>
                            @endauth
                        </div>

                        <!-- Mobile menu button -->
                        <div class="md:hidden flex items-center">
                            <button x-data="{ open: false }" @click="open = !open" class="text-white hover:text-indigo-200 focus:outline-none focus:text-indigo-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <style>
            .nav-link {
                @apply text-white hover:text-indigo-200 px-3 py-2 rounded-md text-sm font-medium transition-colors relative;
            }
            
            .nav-link:hover::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 2px;
                background: white;
                transition: width 0.3s ease;
            }
            
            .nav-link:hover::after {
                width: 80%;
            }
            
            .nav-link.active {
                @apply text-yellow-300;
            }
            
            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 50%;
                transform: translateX(-50%);
                width: 80%;
                height: 2px;
                background: #fcd34d;
            }
        </style>
    </body>
</html>
