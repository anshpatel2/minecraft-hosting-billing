<x-modern-layout title="My Customers">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-teal-600 via-cyan-600 to-blue-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-6 md:mb-0">
                    <h1 class="text-4xl font-bold text-white mb-3">My Customers</h1>
                    <p class="text-teal-100 text-lg">Manage your customer accounts and services</p>
                </div>
                <div>
                    <button class="inline-flex items-center px-6 py-3 bg-white text-teal-600 font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:bg-gray-50">
                        <i class="fas fa-plus mr-2"></i>Add New Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Customer Management Section -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Customer Management</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">View and manage your customer accounts</p>
                        </div>
                        <button class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Add New Customer
                        </button>
                    </div>
                </div>

                <div class="modern-card-body">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    @if(auth()->user()->customers && auth()->user()->customers->count() > 0)
                        <!-- Customer List -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Servers</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monthly Revenue</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach(auth()->user()->customers as $customer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-semibold">{{ substr($customer->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $customer->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">Customer since {{ $customer->created_at->format('M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $customer->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $customer->servers->count() ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            ${{ number_format($customer->monthly_spend ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button class="btn-secondary text-xs mr-2">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                            <button class="btn-secondary text-xs">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Customers Yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                You haven't added any customers yet. Start by adding your first customer to begin managing their accounts.
                            </p>
                            <button class="btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add Your First Customer
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-modern-layout>
