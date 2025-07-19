<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg admin-card">
                    <div class="p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                                <p class="text-gray-600">Here's what's happening with your Minecraft hosting platform today.</p>
                            </div>
                            <div class="hidden md:block">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Grid -->
            <div class="dashboard-grid mb-8">
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-blue-600">Total Users</h4>
                            <p class="text-blue-900">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-green-600">Active Servers</h4>
                            <p class="text-green-900">0</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-purple-600">Monthly Revenue</h4>
                            <p class="text-purple-900">$0</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-orange-600">Support Tickets</h4>
                            <p class="text-orange-900">0</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Management Sections -->
            <div class="dashboard-grid">
                <div class="admin-card fade-in-up">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <span class="admin-badge admin-badge-primary">Active</span>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">User Management</h4>
                    <p class="text-gray-600 mb-4">Manage all users, roles, and permissions across your platform.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="admin-badge admin-badge-info text-xs">{{ \App\Models\User::count() }} Total Users</span>
                        <span class="admin-badge admin-badge-success text-xs">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }} Verified</span>
                    </div>
                    <a href="{{ route('admin.users') }}" class="admin-btn admin-btn-primary w-full justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14l-4-4"></path>
                        </svg>
                        Manage Users
                    </a>
                </div>
                
                <div class="admin-card fade-in-up">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                        </div>
                        <span class="admin-badge admin-badge-warning">Coming Soon</span>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Server Management</h4>
                    <p class="text-gray-600 mb-4">Monitor and manage all Minecraft servers and their configurations.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="admin-badge admin-badge-info text-xs">0 Servers</span>
                        <span class="admin-badge admin-badge-success text-xs">0 Online</span>
                    </div>
                    <button class="admin-btn admin-btn-secondary w-full justify-center" disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Phase 3 Feature
                    </button>
                </div>
                
                <div class="admin-card fade-in-up">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="admin-badge admin-badge-warning">Coming Soon</span>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Billing Overview</h4>
                    <p class="text-gray-600 mb-4">View all transactions, invoices, and billing management.</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="admin-badge admin-badge-info text-xs">$0 Revenue</span>
                        <span class="admin-badge admin-badge-success text-xs">0 Transactions</span>
                    </div>
                    <button class="admin-btn admin-btn-secondary w-full justify-center" disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Phase 4 Feature
                    </button>
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="mt-12">
                <div class="admin-card">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900">Your Administrator Permissions</h4>
                        <span class="admin-badge admin-badge-success">{{ auth()->user()->getAllPermissions()->count() }} Permissions</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach(auth()->user()->getAllPermissions() as $permission)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <svg class="w-4 h-4 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-admin-layout>
