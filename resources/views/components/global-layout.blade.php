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
            /* Modern Dark Theme Variables */
            :root {
                --primary-color: #3b82f6;
                --primary-dark: #1e40af;
                --secondary-color: #8b5cf6;
                --accent-color: #06b6d4;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --error-color: #ef4444;
                --dark-bg: #0f172a;
                --dark-surface: #1e293b;
                --dark-card: #334155;
                --light-text: #f8fafc;
                --muted-text: #94a3b8;
            }

            /* Base Styling */
            body {
                font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                min-height: 100vh;
            }

            /* Navigation Improvements */
            .global-nav {
                background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }

            .nav-link {
                position: relative;
                color: #e2e8f0;
                font-weight: 500;
                padding: 12px 16px;
                border-radius: 8px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
            }

            .nav-link:hover {
                color: #ffffff;
                background: rgba(255, 255, 255, 0.1);
                transform: translateY(-1px);
            }

            .nav-link.active {
                color: #60a5fa;
                background: rgba(96, 165, 250, 0.15);
                box-shadow: 0 0 20px rgba(96, 165, 250, 0.3);
            }

            /* Card Components */
            .admin-card {
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 16px;
                padding: 24px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .admin-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
            }
            
            .admin-form {
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 16px;
                padding: 24px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
            }
            
            /* Info Components */
            .admin-info-item {
                padding: 16px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .admin-info-item:last-child {
                border-bottom: none;
            }
            
            .admin-info-label {
                color: #94a3b8;
                font-size: 0.875rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 8px;
            }
            
            .admin-info-value {
                color: #f8fafc;
                font-size: 1rem;
                font-weight: 500;
            }
            
            /* Button Styles */
            .admin-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 12px 24px;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                text-decoration: none;
            }
            
            .admin-btn-primary {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            }

            .admin-btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6);
            }
            
            .admin-btn-secondary {
                background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(107, 114, 128, 0.4);
            }

            .admin-btn-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(107, 114, 128, 0.6);
            }
            
            /* Alert Styles */
            .admin-alert {
                border-radius: 12px;
                padding: 20px;
                backdrop-filter: blur(10px);
            }
            
            .admin-alert-warning {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
                border: 1px solid rgba(245, 158, 11, 0.3);
                color: #fbbf24;
            }
            
            /* Quick Action Styles */
            .admin-quick-action {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 20px;
                background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                transition: all 0.3s ease;
                text-decoration: none;
                color: inherit;
            }

            .admin-quick-action:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
                border-color: rgba(255, 255, 255, 0.2);
            }

            /* Header Gradients */
            .gradient-header {
                background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #06b6d4 100%);
                position: relative;
                overflow: hidden;
            }

            .gradient-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><path d="M0,0v46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1047.17,0,1000,0Z"></path></svg>') repeat-x;
                animation: wave 20s linear infinite;
            }

            @keyframes wave {
                0% { transform: translateX(0); }
                100% { transform: translateX(-1000px); }
            }

            /* Status Badges */
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .status-verified {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            }

            .status-unverified {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            }

            .status-member {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            }

            /* Avatar */
            .user-avatar {
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #06b6d4 100%);
                box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
                border: 3px solid rgba(255, 255, 255, 0.2);
            }

            /* Scrollbar Styling */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: #1e293b;
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .admin-card, .admin-form {
                    padding: 16px;
                    margin: 8px;
                    border-radius: 12px;
                }

                .admin-btn {
                    padding: 10px 20px;
                    font-size: 0.8rem;
                }
            }
        </style>
            
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
            
            /* Admin Component Styles */
            .admin-info-item {
                margin-bottom: 0.5rem;
            }
            
            .admin-info-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
                margin-bottom: 0.25rem;
            }
            
            .dark .admin-info-label {
                color: #d1d5db;
            }
            
            .admin-info-value {
                color: #111827;
                font-weight: 500;
            }
            
            .dark .admin-info-value {
                color: #f9fafb;
            }
            
            .admin-btn {
                display: inline-flex;
                align-items: center;
                padding: 0.5rem 1rem;
                border: 1px solid transparent;
                border-radius: 0.375rem;
                font-weight: 600;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                transition: all 0.15s ease-in-out;
                text-decoration: none;
            }
            
            .admin-btn-primary {
                background-color: #2563eb;
                color: white;
            }
            
            .admin-btn-primary:hover {
                background-color: #1d4ed8;
                color: white;
                text-decoration: none;
            }
            
            .admin-btn-primary:active {
                background-color: #1e3a8a;
            }
            
            .admin-btn-primary:focus {
                outline: none;
                border-color: #1e3a8a;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
            }
            
            .admin-btn-secondary {
                background-color: #4b5563;
                color: white;
            }
            
            .admin-btn-secondary:hover {
                background-color: #374151;
                color: white;
                text-decoration: none;
            }
            
            .admin-btn-secondary:active {
                background-color: #111827;
            }
            
            .admin-btn-secondary:focus {
                outline: none;
                border-color: #111827;
                box-shadow: 0 0 0 3px rgba(75, 85, 99, 0.5);
            }
            
            .admin-alert {
                padding: 1rem;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
            }
            
            .admin-alert-warning {
                background-color: #fefce8;
                border: 1px solid #fde047;
                color: #92400e;
            }
            
            .admin-quick-action {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                background-color: #f9fafb;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                text-decoration: none;
                color: inherit;
            }
            
            .admin-quick-action:hover {
                background-color: #f3f4f6;
                text-decoration: none;
                color: inherit;
                transform: translateY(-1px);
            }
            
            .dark .admin-quick-action {
                background-color: #374151;
            }
            
            .dark .admin-quick-action:hover {
                background-color: #4b5563;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-gradient-to-r from-slate-900 via-purple-900 to-slate-900 shadow-xl global-nav">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16" x-data="{ mobileMenuOpen: false }">
                        <!-- Logo and Brand -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-white text-xl font-bold tracking-tight">MineCraft Hosting</span>
                                    <p class="text-purple-200 text-xs hidden lg:block">Professional Server Management</p>
                                </div>
                            </div>
                        </div>

                        <!-- Desktop Navigation -->
                        <div class="hidden md:flex items-center space-x-6">
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
                                    <button @click="open = !open" class="relative text-white hover:text-purple-200 transition-all duration-300 p-2 rounded-lg hover:bg-white hover:bg-opacity-10">
                                        <i class="fas fa-bell text-lg"></i>
                                        @if($unreadNotifications > 0)
                                            <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-lg animate-pulse">
                                                {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                                            </span>
                                        @endif
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 backdrop-blur-sm">
                                        
                                        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50 rounded-t-xl">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
                                                @if($unreadNotifications > 0)
                                                    <span class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                                                        {{ $unreadNotifications }} new
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="max-h-64 overflow-y-auto">
                                            @forelse($recentNotifications as $notification)
                                                <div class="p-3 border-b border-gray-50 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200 {{ $notification->read_at ? '' : 'bg-gradient-to-r from-blue-50 to-indigo-50' }}">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-md">
                                                                <i class="fas fa-bell text-white text-xs"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 flex-1">
                                                            <p class="text-sm font-semibold text-gray-900">
                                                                {{ $notification->data['title'] ?? 'Notification' }}
                                                            </p>
                                                            <p class="text-sm text-gray-600 mt-1">
                                                                {{ $notification->data['message'] ?? 'No message' }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-2">
                                                                {{ $notification->created_at->diffForHumans() }}
                                                            </p>
                                                        </div>
                                                        @if(!$notification->read_at)
                                                            <div class="flex-shrink-0">
                                                                <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full shadow-sm"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="p-6 text-center text-gray-500">
                                                    <i class="fas fa-bell-slash text-3xl mb-3 text-gray-300"></i>
                                                    <p class="font-medium">No notifications yet</p>
                                                    <p class="text-sm">We'll notify you when something happens</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        
                                        @if($recentNotifications->count() > 0)
                                            <div class="p-3 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-purple-50 rounded-b-xl">
                                                <a href="{{ route('admin.notifications.index') }}" class="block text-center text-sm text-purple-600 hover:text-purple-800 font-semibold transition-colors">
                                                    View all notifications
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- User Dropdown -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="flex items-center text-white hover:text-purple-200 transition-all duration-300 px-3 py-2 rounded-lg hover:bg-white hover:bg-opacity-10">
                                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mr-3 shadow-lg">
                                            <span class="text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="text-left hidden lg:block">
                                            <span class="font-semibold text-sm block">{{ Auth::user()->name }}</span>
                                            <span class="text-xs text-purple-200">
                                                @if(Auth::user()->roles->first())
                                                    {{ ucfirst(Auth::user()->roles->first()->name) }}
                                                @endif
                                            </span>
                                        </div>
                                        <i class="fas fa-chevron-down ml-2 text-sm"></i>
                                    </button>
                                    
                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 backdrop-blur-sm">
                                        
                                        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50 rounded-t-xl">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mr-3 shadow-lg">
                                                    <span class="text-lg font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                                    <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                                    @if(Auth::user()->roles->first())
                                                        <span class="inline-block bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs px-3 py-1 rounded-full mt-1 font-medium shadow-sm">
                                                            {{ ucfirst(Auth::user()->roles->first()->name) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="p-2">
                                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 rounded-lg transition-all duration-200 group">
                                                <i class="fas fa-user mr-3 text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                                                <span class="font-medium">Profile Settings</span>
                                            </a>
                                            
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 rounded-lg transition-all duration-200 group">
                                                    <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-red-500 transition-colors"></i>
                                                    <span class="font-medium">Sign Out</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-white hover:text-purple-200 font-medium px-4 py-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition-all duration-300">Login</a>
                                <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">Register</a>
                            @endauth
                        </div>

                        <!-- Mobile menu button -->
                        <div class="md:hidden flex items-center">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-purple-200 focus:outline-none focus:text-purple-200 p-2 rounded-lg hover:bg-white hover:bg-opacity-10 transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Navigation Menu -->
                    <div x-show="mobileMenuOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="md:hidden bg-slate-800 bg-opacity-95 backdrop-blur-sm border-t border-purple-500 border-opacity-30">
                        
                        <div class="px-4 py-4 space-y-2">
                            @if(Auth::check() && Auth::user()->hasRole('admin'))
                                <!-- Admin Mobile Navigation -->
                                <a href="{{ url('/dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                                </a>
                                <a href="{{ url('/admin/manage/users') }}" class="mobile-nav-link {{ request()->is('admin/manage/users') ? 'active' : '' }}">
                                    <i class="fas fa-users mr-3"></i>Users
                                </a>
                                <a href="{{ url('/admin/manage/plans') }}" class="mobile-nav-link {{ request()->is('admin/manage/plans') ? 'active' : '' }}">
                                    <i class="fas fa-box mr-3"></i>Plans
                                </a>
                                <a href="{{ url('/admin/manage/orders') }}" class="mobile-nav-link {{ request()->is('admin/manage/orders') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart mr-3"></i>Orders
                                </a>
                                <a href="{{ url('/admin/manage/servers') }}" class="mobile-nav-link {{ request()->is('admin/manage/servers') ? 'active' : '' }}">
                                    <i class="fas fa-server mr-3"></i>Servers
                                </a>
                                <a href="{{ url('/admin/manage/billing') }}" class="mobile-nav-link {{ request()->is('admin/manage/billing') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card mr-3"></i>Billing
                                </a>
                            @elseif(Auth::check() && Auth::user()->hasRole('reseller'))
                                <!-- Reseller Mobile Navigation -->
                                <a href="{{ url('/reseller/dashboard') }}" class="mobile-nav-link {{ request()->is('reseller/dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                                </a>
                                <a href="{{ url('/reseller/customers') }}" class="mobile-nav-link {{ request()->is('reseller/customers') ? 'active' : '' }}">
                                    <i class="fas fa-users mr-3"></i>Customers
                                </a>
                                <a href="{{ url('/reseller/servers') }}" class="mobile-nav-link {{ request()->is('reseller/servers') ? 'active' : '' }}">
                                    <i class="fas fa-server mr-3"></i>Servers
                                </a>
                                <a href="{{ url('/reseller/billing') }}" class="mobile-nav-link {{ request()->is('reseller/billing') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card mr-3"></i>Billing
                                </a>
                            @else
                                <!-- Customer Mobile Navigation -->
                                <a href="{{ url('/dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                                </a>
                                <a href="{{ url('/servers') }}" class="mobile-nav-link {{ request()->is('servers') ? 'active' : '' }}">
                                    <i class="fas fa-server mr-3"></i>Servers
                                </a>
                                <a href="{{ url('/billing') }}" class="mobile-nav-link {{ request()->is('billing') ? 'active' : '' }}">
                                    <i class="fas fa-credit-card mr-3"></i>Billing
                                </a>
                                <a href="{{ url('/support') }}" class="mobile-nav-link {{ request()->is('support') ? 'active' : '' }}">
                                    <i class="fas fa-life-ring mr-3"></i>Support
                                </a>
                            @endif
                            
                            @auth
                                <div class="border-t border-purple-500 border-opacity-30 pt-4 mt-4">
                                    <div class="flex items-center px-3 py-2 mb-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium text-sm">{{ Auth::user()->name }}</p>
                                            <p class="text-purple-200 text-xs">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                                        <i class="fas fa-user mr-3"></i>Profile Settings
                                    </a>
                                    
                                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                        @csrf
                                        <button type="submit" class="w-full mobile-nav-link text-left">
                                            <i class="fas fa-sign-out-alt mr-3"></i>Sign Out
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="border-t border-purple-500 border-opacity-30 pt-4 mt-4 space-y-2">
                                    <a href="{{ route('login') }}" class="mobile-nav-link">
                                        <i class="fas fa-sign-in-alt mr-3"></i>Login
                                    </a>
                                    <a href="{{ route('register') }}" class="mobile-nav-link bg-gradient-to-r from-blue-500 to-purple-600">
                                        <i class="fas fa-user-plus mr-3"></i>Register
                                    </a>
                                </div>
                            @endauth
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
            /* Enhanced Navigation Styles */
            .nav-link {
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                transition: all 0.3s ease;
                position: relative;
                background: transparent;
                border: 1px solid transparent;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
            }
            
            .nav-link:hover {
                color: #c4b5fd;
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(139, 92, 246, 0.3);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
                text-decoration: none;
            }
            
            .nav-link.active {
                color: #fcd34d;
                background: rgba(255, 255, 255, 0.15);
                border-color: rgba(252, 211, 77, 0.5);
                box-shadow: 0 4px 16px rgba(252, 211, 77, 0.3);
            }
            
            .nav-link.active::before {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 50%;
                transform: translateX(-50%);
                width: 80%;
                height: 2px;
                background: linear-gradient(90deg, #fcd34d, #f59e0b);
                border-radius: 1px;
            }

            /* Mobile Navigation Styles */
            .mobile-nav-link {
                display: flex;
                align-items: center;
                width: 100%;
                padding: 0.75rem 1rem;
                color: white;
                font-size: 0.875rem;
                font-weight: 500;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                background: transparent;
                border: 1px solid transparent;
                text-decoration: none;
            }
            
            .mobile-nav-link:hover {
                color: #c4b5fd;
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(139, 92, 246, 0.3);
                transform: translateX(4px);
                text-decoration: none;
            }
            
            .mobile-nav-link.active {
                color: #fcd34d;
                background: rgba(255, 255, 255, 0.15);
                border-color: rgba(252, 211, 77, 0.5);
                box-shadow: 0 2px 8px rgba(252, 211, 77, 0.2);
            }

            /* Notification Animation */
            @keyframes bounce-subtle {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-2px); }
            }
            
            .notification-badge {
                animation: bounce-subtle 2s infinite;
            }

            /* Smooth scrollbar for dropdowns */
            .max-h-64::-webkit-scrollbar {
                width: 4px;
            }
            
            .max-h-64::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 2px;
            }
            
            .max-h-64::-webkit-scrollbar-thumb {
                background: linear-gradient(to bottom, #8b5cf6, #a855f7);
                border-radius: 2px;
            }
            
            .max-h-64::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(to bottom, #7c3aed, #9333ea);
            }
        </style>
    </body>
</html>
