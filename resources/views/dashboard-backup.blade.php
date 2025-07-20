<x-global-layout title="Dashboard">
    <!-- Hero Section -->
    <div class="hero-gradient text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-xl text-white text-opacity-90 mb-2">You're logged in as: <span class="font-semibold text-yellow-300">{{ auth()->user()->getPrimaryRole() }}</span></p>
                <p class="text-lg text-white text-opacity-80">Manage your Minecraft empire from here</p>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="py-12 -mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Quick Actions Cards -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if(auth()->user()->canAccessAdminPanel())
                    <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-red-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">Admin Panel</h4>
                        </div>
                        <p class="text-gray-600 mb-4">Manage users, servers, and system settings with full administrative control</p>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md">
                            <i class="fas fa-cog mr-2"></i>Access Admin Panel
                        </a>
                    </div>
                    @endif

                    @if(auth()->user()->canAccessResellerPanel())
                    <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-handshake text-blue-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">Reseller Panel</h4>
                        </div>
                        <p class="text-gray-600 mb-4">Manage your customers, track commissions and grow your business</p>
                        <a href="{{ route('reseller.dashboard') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md">
                            <i class="fas fa-chart-line mr-2"></i>Access Reseller Panel
                        </a>
                    </div>
                    @endif

                    <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-server text-green-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">Create Server</h4>
                        </div>
                        <p class="text-gray-600 mb-4">Launch a new Minecraft server with your preferred configuration</p>
                        <button class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md">
                            <i class="fas fa-plus mr-2"></i>Create Server
                        </button>
                    </div>

                    <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">Billing</h4>
                        </div>
                        <p class="text-gray-600 mb-4">Manage your subscription, payment methods and billing history</p>
                        <button class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md">
                            <i class="fas fa-dollar-sign mr-2"></i>Manage Billing
                        </button>
                    </div>

                    <div class="glass-card hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-yellow-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-life-ring text-yellow-600 text-xl"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg">Support</h4>
                        </div>
                        <p class="text-gray-600 mb-4">Get help from our support team or browse our knowledge base</p>
                        <button class="inline-flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md">
                            <i class="fas fa-headset mr-2"></i>Get Support
                        </button>
                    </div>
                </div>
            </div>

            <!-- Account Overview -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Account Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Account Balance -->
                    <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Account Balance</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format(auth()->user()->balance ?? 0, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-wallet text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Active Servers -->
                    <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Active Servers</p>
                                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->servers_count ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-server text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Spend -->
                    <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">This Month</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format(auth()->user()->monthly_spend ?? 0, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Paid -->
                    <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Paid</p>
                                <p class="text-2xl font-bold text-gray-900">${{ number_format(auth()->user()->total_paid ?? 0, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-card p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Recent Activity</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-check text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Account Created</p>
                            <p class="text-sm text-gray-600">Welcome to our Minecraft hosting platform!</p>
                            <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-global-layout>
