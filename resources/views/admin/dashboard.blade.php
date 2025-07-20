<x-modern-layout title="Admin Dashboard">
    <!-- Dashboard Header -->
    <div class="mb-8 fade-in">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Admin Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Welcome back! Here's what's happening with your platform today.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8 fade-in">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Total Users</span>
                <div class="stat-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ \App\Models\User::count() }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +{{ \App\Models\User::where('created_at', '>=', now()->subDays(7))->count() }} this week
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Total Orders</span>
                <div class="stat-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-value">{{ \App\Models\Order::count() }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +{{ \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count() }} this week
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Active Plans</span>
                <div class="stat-icon" style="background: var(--gradient-warning);">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <div class="stat-value">{{ \App\Models\Plan::where('is_active', true)->count() }}</div>
            <div class="stat-change">
                <i class="fas fa-equals"></i>
                {{ \App\Models\Plan::count() }} total plans
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Revenue (30d)</span>
                <div class="stat-icon" style="background: var(--gradient-info);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-value">${{ number_format(\App\Models\Order::where('status', 'completed')->where('created_at', '>=', now()->subDays(30))->sum('amount'), 2) }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                Last 30 days
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="modern-card mb-8 fade-in">
        <div class="card-header">
            <h2 class="card-title">
                <div class="card-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                Quick Actions
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your platform efficiently</p>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.users') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-blue-500">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="quick-action-content">
                        <h4>Manage Users</h4>
                        <p>Add, edit, and manage user accounts</p>
                    </div>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-green-500">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="quick-action-content">
                        <h4>Manage Orders</h4>
                        <p>View and process customer orders</p>
                    </div>
                </a>

                <a href="{{ route('admin.plans.index') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-purple-500">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="quick-action-content">
                        <h4>Manage Plans</h4>
                        <p>Create and edit hosting plans</p>
                    </div>
                </a>

                <button onclick="showComingSoon('System Settings')" class="quick-action-btn text-left">
                    <div class="quick-action-icon bg-yellow-500">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="quick-action-content">
                        <h4>System Settings</h4>
                        <p>Configure platform settings</p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Activity & System Status -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8 fade-in">
        <!-- Recent Users -->
        <div class="modern-card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    Recent Users
                </h3>
                <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @forelse(\App\Models\User::latest()->limit(5)->get() as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $user->getPrimaryRole() }}
                            </span>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No users found</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Users will appear here when they register</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="modern-card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="card-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    Recent Orders
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @forelse(\App\Models\Order::with('user', 'plan')->latest()->limit(5)->get() as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">#{{ $order->id }} - {{ $order->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->plan->name ?? 'Unknown Plan' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">${{ number_format($order->amount, 2) }}</p>
                            <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full 
                                @if($order->status === 'active') bg-green-100 text-green-800
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No orders found</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Orders will appear here when customers make purchases</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="modern-card fade-in">
        <div class="card-header">
            <h3 class="card-title">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                System Overview
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Real-time platform statistics</p>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- User Distribution -->
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">User Distribution</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Admins:</span>
                            <span class="font-medium">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'Admin'); })->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Customers:</span>
                            <span class="font-medium">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'Customer'); })->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Resellers:</span>
                            <span class="font-medium">{{ \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'Reseller'); })->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-100 dark:border-green-800">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Order Status</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Active:</span>
                            <span class="font-medium text-green-600">{{ \App\Models\Order::where('status', 'active')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Pending:</span>
                            <span class="font-medium text-yellow-600">{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Completed:</span>
                            <span class="font-medium text-blue-600">{{ \App\Models\Order::where('status', 'completed')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Plan Status -->
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-100 dark:border-purple-800">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Plan Status</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Active Plans:</span>
                            <span class="font-medium text-green-600">{{ \App\Models\Plan::where('is_active', true)->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Inactive Plans:</span>
                            <span class="font-medium text-red-600">{{ \App\Models\Plan::where('is_active', false)->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Plans:</span>
                            <span class="font-medium">{{ \App\Models\Plan::count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coming Soon Modal -->
    <div id="comingSoonModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md mx-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2" id="featureName">Feature Coming Soon</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">This feature is currently under development and will be available soon!</p>
                <button onclick="closeComingSoonModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                    Got it!
                </button>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .quick-action-btn {
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-1px);
            border-color: #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .modern-card {
            transition: box-shadow 0.2s ease-in-out;
        }
        
        .modern-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function showComingSoon(featureName) {
            document.getElementById('featureName').textContent = featureName + ' Coming Soon';
            document.getElementById('comingSoonModal').classList.remove('hidden');
            document.getElementById('comingSoonModal').classList.add('flex');
        }

        function closeComingSoonModal() {
            document.getElementById('comingSoonModal').classList.add('hidden');
            document.getElementById('comingSoonModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('comingSoonModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeComingSoonModal();
            }
        });

        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            // You can add AJAX calls here to refresh statistics
            console.log('Stats refreshed at: ' + new Date().toLocaleTimeString());
        }, 30000);
    </script>
    @endpush
</x-modern-layout>