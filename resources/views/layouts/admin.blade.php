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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js for interactive components -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Admin Theme CSS -->
        <link href="{{ asset('assets/css/admin-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/notification-enhancement.css') }}" rel="stylesheet">
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
                                
                                <!-- Notifications Dropdown -->
                                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                    <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.notifications.*') ? 'border-indigo-400 text-gray-900 focus:outline-none focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300' }}">
                                        {{ __('Notifications') }}
                                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition class="absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                                        <a href="{{ route('admin.notifications.overview') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Overview</a>
                                        <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Notifications</a>
                                        <a href="{{ route('admin.notifications.global') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Notifications</a>
                                        <a href="{{ route('admin.notifications.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Send Notification</a>
                                    </div>
                                </div>
                                
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
                                <button id="themeToggle" class="text-gray-500 hover:text-gray-700 transition-colors duration-200" title="Toggle theme">
                                    <svg id="themeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Main Site Link -->
                                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200" title="Main Site">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </a>
                                
                                <!-- Enhanced Notifications -->
                                <div class="relative" 
                                     x-data="notificationComponent()" 
                                     x-init="fetchNotifications()">
                                    <button @click="showDropdown = !showDropdown" 
                                            class="text-gray-500 hover:text-gray-700 transition-colors duration-200 relative" 
                                            title="Notifications">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                                        </svg>
                                        <span x-show="unreadCount > 0" 
                                              x-text="unreadCount > 99 ? '99+' : unreadCount"
                                              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center min-w-[16px] animate-pulse">
                                        </span>
                                    </button>
                                    
                                    <!-- Notification Dropdown -->
                                    <div x-show="showDropdown" 
                                         @click.away="showDropdown = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        
                                        <!-- Header -->
                                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                            <span x-text="unreadCount + ' unread'" class="text-xs text-gray-500"></span>
                                        </div>
                                        
                                        <!-- Notifications List -->
                                        <div class="max-h-96 overflow-y-auto">
                                            <template x-for="notification in notifications.slice(0, 5)" :key="notification.id">
                                                <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" 
                                                     :class="!notification.read_at ? 'bg-blue-50 border-l-4 border-l-blue-500' : ''"
                                                     @click="markAsRead(notification.id); window.location.href = getNotificationUrl(notification)">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                                 :class="getNotificationBgColor(notification.type)">
                                                                <i :class="getNotificationIcon(notification.type)" class="text-sm"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900" x-text="notification.data.title || 'Notification'"></p>
                                                            <p class="text-sm text-gray-600 truncate" x-text="notification.data.message || 'New notification received'"></p>
                                                            <p class="text-xs text-gray-500 mt-1" x-text="formatTime(notification.created_at)"></p>
                                                        </div>
                                                        <div x-show="!notification.read_at" class="flex-shrink-0">
                                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-500">
                                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                                                </svg>
                                                <p class="text-sm">No notifications</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Footer -->
                                        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                                            <div class="flex justify-between items-center">
                                                <a href="{{ route('admin.notifications.index') }}" 
                                                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                    View all notifications
                                                </a>
                                                <button x-show="unreadCount > 0" 
                                                        @click="markAllAsRead()" 
                                                        class="text-sm text-gray-600 hover:text-gray-800">
                                                    Mark all read
                                                </button>
                                            </div>
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
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/js/admin-datatables.js') }}"></script>
        
        <!-- Theme Toggle & Notification Scripts -->
        <script>
            // Notification Component for Alpine.js
            function notificationComponent() {
                return {
                    showDropdown: false,
                    unreadCount: {{ auth()->user()->unreadNotifications->count() }},
                    notifications: [],
                    loading: false,
                    
                    fetchNotifications() {
                        this.loading = true;
                        fetch('{{ route("admin.notifications.api") }}')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                this.notifications = data.notifications || [];
                                this.unreadCount = data.unread_count || 0;
                                this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error fetching notifications:', error);
                                this.loading = false;
                            });
                    },
                    
                    markAsRead(notificationId) {
                        fetch(`/admin/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).then(() => {
                            const notification = this.notifications.find(n => n.id === notificationId);
                            if (notification && !notification.read_at) {
                                notification.read_at = new Date().toISOString();
                                this.unreadCount = Math.max(0, this.unreadCount - 1);
                            }
                        }).catch(error => {
                            console.error('Error marking notification as read:', error);
                        });
                    },
                    
                    markAllAsRead() {
                        fetch('{{ route("admin.notifications.mark-all-read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }).then(response => {
                            if (response.ok) {
                                this.notifications.forEach(notification => {
                                    notification.read_at = new Date().toISOString();
                                });
                                this.unreadCount = 0;
                            }
                        }).catch(error => {
                            console.error('Error marking all notifications as read:', error);
                        });
                    },
                    
                    getNotificationIcon(type) {
                        const icons = {
                            'user_verified': 'fas fa-user-check',
                            'plan_purchased': 'fas fa-credit-card',
                            'admin_message': 'fas fa-envelope',
                            'system': 'fas fa-cog',
                            'default': 'fas fa-bell'
                        };
                        return icons[type] || icons.default;
                    },
                    
                    getNotificationBgColor(type) {
                        const colors = {
                            'user_verified': 'bg-green-100 text-green-600',
                            'plan_purchased': 'bg-blue-100 text-blue-600',
                            'admin_message': 'bg-purple-100 text-purple-600',
                            'system': 'bg-gray-100 text-gray-600',
                            'default': 'bg-blue-100 text-blue-600'
                        };
                        return colors[type] || colors.default;
                    },
                    
                    getNotificationUrl(notification) {
                        const urls = {
                            'user_verified': '{{ route("admin.users") }}',
                            'plan_purchased': '{{ route("admin.dashboard") }}',
                            'admin_message': '{{ route("admin.notifications.index") }}',
                            'system': '{{ route("admin.dashboard") }}'
                        };
                        return urls[notification.type] || '{{ route("admin.notifications.index") }}';
                    },
                    
                    formatTime(timestamp) {
                        const date = new Date(timestamp);
                        const now = new Date();
                        const diff = now - date;
                        const minutes = Math.floor(diff / 60000);
                        const hours = Math.floor(diff / 3600000);
                        const days = Math.floor(diff / 86400000);
                        
                        if (minutes < 1) return 'Just now';
                        if (minutes < 60) return `${minutes}m ago`;
                        if (hours < 24) return `${hours}h ago`;
                        if (days < 7) return `${days}d ago`;
                        return date.toLocaleDateString();
                    }
                }
            }
            
            // Theme Toggle Functionality
            document.addEventListener('DOMContentLoaded', function() {
                const themeToggle = document.getElementById('themeToggle');
                const themeIcon = document.getElementById('themeIcon');
                const body = document.body;
                
                // Check for saved theme preference or default to 'light'
                const currentTheme = localStorage.getItem('theme') || 'light';
                body.setAttribute('data-theme', currentTheme);
                updateThemeIcon(currentTheme);
                
                themeToggle.addEventListener('click', function() {
                    const current = body.getAttribute('data-theme');
                    const newTheme = current === 'dark' ? 'light' : 'dark';
                    
                    body.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);
                });
                
                function updateThemeIcon(theme) {
                    if (theme === 'dark') {
                        // Sun icon for light mode
                        themeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>`;
                    } else {
                        // Moon icon for dark mode
                        themeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>`;
                    }
                }
            });
        </script>
    </body>
</html>
