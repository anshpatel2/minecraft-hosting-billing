<x-modern-layout title="Reseller Dashboard">
    <!-- Reseller Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-teal-600 via-cyan-600 to-blue-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">Reseller Dashboard</h1>
                <p class="text-teal-100 text-lg">Manage your customers and services</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Reseller Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stats-card bg-gradient-to-br from-blue-500 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Customers</p>
                            <p class="text-3xl font-bold text-white">{{ auth()->user()->customers->count() ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-users text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-green-500 to-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Servers</p>
                            <p class="text-3xl font-bold text-white">{{ \App\Models\Server::whereHas('user', function($q) { $q->where('reseller_id', auth()->id()); })->count() ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-server text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-purple-500 to-purple-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Monthly Revenue</p>
                            <p class="text-3xl font-bold text-white">${{ number_format(auth()->user()->customers->sum('monthly_spend') * 0.15, 2) }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-chart-line text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-orange-500 to-orange-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Commission Rate</p>
                            <p class="text-3xl font-bold text-white">{{ auth()->user()->commission_rate ?? '15' }}%</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-percentage text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="modern-card mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Customer Management</h4>
                            <p class="text-sm text-blue-600 mt-2">Manage your customers and their accounts</p>
                            <a href="{{ route('reseller.customers') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                View Customers →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Server Management</h4>
                            <p class="text-sm text-green-600 mt-2">Create and manage servers for customers</p>
                            <a href="{{ route('customer.servers') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mt-2 inline-block">
                                Manage Servers →
                            </a>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="font-semibold mb-4">Your Role: {{ auth()->user()->getPrimaryRole() }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(auth()->user()->getAllPermissions() as $permission)
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-modern-layout>
