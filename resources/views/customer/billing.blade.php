<x-modern-layout title="Billing">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">Billing & Payments</h1>
                <p class="text-purple-100 text-lg">View your billing information and payment history</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Billing Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="stats-card bg-gradient-to-br from-green-500 to-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Current Balance</p>
                            <p class="text-3xl font-bold text-white">${{ auth()->user()->balance ?? '0.00' }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-wallet text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-blue-500 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Monthly Spend</p>
                            <p class="text-3xl font-bold text-white">${{ auth()->user()->monthly_spend ?? '0.00' }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-credit-card text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-purple-500 to-purple-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Paid</p>
                            <p class="text-3xl font-bold text-white">${{ auth()->user()->total_paid ?? '0.00' }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-chart-line text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-orange-500 to-orange-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">Outstanding</p>
                            <p class="text-3xl font-bold text-white">${{ auth()->user()->outstanding_balance ?? '0.00' }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Overview -->
            <div class="modern-card mb-8">
                <div class="modern-card-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Billing Overview</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your account billing and payments</p>
                        </div>
                        <button class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Add Funds
                        </button>
                    </div>
                </div>

                <div class="modern-card-body">
                    @if(auth()->user()->invoices && auth()->user()->invoices->count() > 0)
                        <!-- Invoice History -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach(auth()->user()->invoices as $invoice)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            #{{ $invoice->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $invoice->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            ${{ number_format($invoice->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                @if($invoice->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($invoice->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button class="btn-secondary text-xs">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                            @if($invoice->status === 'pending')
                                            <button class="btn-primary text-xs ml-2">
                                                <i class="fas fa-credit-card mr-1"></i>Pay Now
                                            </button>
                                            @endif
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
                                <i class="fas fa-receipt text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Billing History</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                You don't have any billing activity yet. Your invoices and payment history will appear here.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Payment Methods</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your payment methods</p>
                        </div>
                        <button class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Add Payment Method
                        </button>
                    </div>
                </div>

                <div class="modern-card-body">
                    @if(auth()->user()->payment_methods && auth()->user()->payment_methods->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(auth()->user()->payment_methods as $method)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-credit-card text-white"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">•••• •••• •••• {{ $method->last_four }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $method->brand }} • Expires {{ $method->exp_month }}/{{ $method->exp_year }}</p>
                                        </div>
                                    </div>
                                    @if($method->is_default)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                        Default
                                    </span>
                                    @endif
                                </div>
                                <div class="flex space-x-2 mt-4">
                                    @if(!$method->is_default)
                                    <button class="btn-secondary text-xs flex-1">
                                        <i class="fas fa-star mr-1"></i>Set Default
                                    </button>
                                    @endif
                                    <button class="btn-secondary text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-credit-card text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Payment Methods</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Add a payment method to make purchases and payments.</p>
                            <button class="btn-primary">
                                <i class="fas fa-plus mr-2"></i>Add Payment Method
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-modern-layout>
