<div class="admin-card">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Billing & Invoice Management</h2>
        <button wire:click="showCreateForm" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Invoice
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Billing Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white border rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['pending_payments'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['monthly_revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-lg p-6 shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Refunded Amount</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['refunded_amount'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <div class="flex items-center space-x-4">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search invoices, customers..." 
                class="input-field w-64"
            >
            
            <select wire:model.live="filterStatus" class="input-field">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            
            <select wire:model.live="filterUser" class="input-field">
                <option value="">All Customers</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('id')" class="sortable-header">
                        Invoice #
                        @if($sortField === 'id')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Customer
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Plan
                    </th>
                    <th wire:click="sortBy('total_price')" class="sortable-header">
                        Amount
                        @if($sortField === 'total_price')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Payment Method
                    </th>
                    <th wire:click="sortBy('created_at')" class="sortable-header">
                        Date
                        @if($sortField === 'created_at')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                            <div class="text-sm text-gray-500">{{ $order->billing_cycle }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                    {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->plan->name ?? 'No Plan' }}</div>
                            <div class="text-sm text-gray-500">Qty: {{ $order->quantity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ number_format($order->total_price, 2) }}</div>
                            @if($order->refund_amount)
                                <div class="text-sm text-red-500">Refunded: ${{ number_format($order->refund_amount, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                       ($order->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                    {{ ucfirst($order->status) }}
                                    <svg class="ml-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-10 mt-1 w-32 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                                    <button wire:click="updateOrderStatus({{ $order->id }}, 'pending')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pending</button>
                                    <button wire:click="updateOrderStatus({{ $order->id }}, 'processing')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Processing</button>
                                    <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Completed</button>
                                    <button wire:click="updateOrderStatus({{ $order->id }}, 'failed')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Failed</button>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucfirst($order->payment_method) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y') }}</div>
                            @if($order->due_date)
                                <div class="text-sm text-gray-500">Due: {{ Carbon\Carbon::parse($order->due_date)->format('M j, Y') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                @if($order->status === 'pending')
                                    <button wire:click="markAsPaid({{ $order->id }})" class="text-green-600 hover:text-green-900">Pay</button>
                                @endif
                                @if($order->status === 'completed')
                                    <button wire:click="showRefundForm({{ $order->id }})" class="text-red-600 hover:text-red-900">Refund</button>
                                @endif
                                <button wire:click="showViewForm({{ $order->id }})" class="text-blue-600 hover:text-blue-900">View</button>
                                <button wire:click="sendInvoice({{ $order->id }})" class="text-indigo-600 hover:text-indigo-900">Send</button>
                                <button wire:click="generatePDF({{ $order->id }})" class="text-purple-600 hover:text-purple-900">PDF</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No invoices found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    <!-- Create Invoice Modal -->
    @if($showCreateModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-2xl">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Create New Invoice</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="createInvoice" class="modal-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                            <select wire:model="user_id" class="input-field">
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                            <select wire:model.live="plan_id" class="input-field">
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }}</option>
                                @endforeach
                            </select>
                            @error('plan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" wire:model.live="quantity" class="input-field" min="1" max="100">
                            @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Price</label>
                            <input type="number" wire:model="total_price" class="input-field" step="0.01" min="0">
                            @error('total_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                            <select wire:model="billing_cycle" class="input-field">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            @error('billing_cycle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model="status" class="input-field">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select wire:model="payment_method" class="input-field">
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cryptocurrency">Cryptocurrency</option>
                                <option value="other">Other</option>
                            </select>
                            @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" wire:model="due_date" class="input-field">
                            @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea wire:model="notes" class="input-field" rows="3" placeholder="Additional notes or instructions..."></textarea>
                        @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Invoice Modal -->
    @if($showViewModal)
        @php
            $order = \App\Models\Order::with(['user', 'plan'])->find($editingOrderId);
        @endphp
        @if($order)
            <div class="modal-overlay">
                <div class="modal-content max-w-3xl">
                    <div class="modal-header">
                        <h3 class="text-lg font-medium">Invoice #{{ $order->id }}</h3>
                        <button wire:click="closeModal" class="modal-close">&times;</button>
                    </div>
                    
                    <div class="modal-body space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Invoice Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Invoice Information</h4>
                                <div class="space-y-2">
                                    <div><span class="text-sm text-gray-500">Invoice ID:</span> <span class="font-medium">#{{ $order->id }}</span></div>
                                    <div><span class="text-sm text-gray-500">Status:</span> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                               ($order->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div><span class="text-sm text-gray-500">Total Amount:</span> <span class="font-medium">${{ number_format($order->total_price, 2) }}</span></div>
                                    <div><span class="text-sm text-gray-500">Billing Cycle:</span> <span class="font-medium">{{ ucfirst($order->billing_cycle) }}</span></div>
                                    <div><span class="text-sm text-gray-500">Payment Method:</span> <span class="font-medium">{{ ucfirst($order->payment_method) }}</span></div>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Customer Information</h4>
                                <div class="space-y-2">
                                    <div><span class="text-sm text-gray-500">Name:</span> <span class="font-medium">{{ $order->user->name ?? 'Unknown' }}</span></div>
                                    <div><span class="text-sm text-gray-500">Email:</span> <span class="font-medium">{{ $order->user->email ?? 'N/A' }}</span></div>
                                    <div><span class="text-sm text-gray-500">Customer ID:</span> <span class="font-medium">#{{ $order->user->id ?? 'N/A' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan Details -->
                        @if($order->plan)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Plan Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><span class="text-sm text-gray-500">Plan Name:</span> <span class="font-medium">{{ $order->plan->name }}</span></div>
                                    <div><span class="text-sm text-gray-500">Quantity:</span> <span class="font-medium">{{ $order->quantity }}</span></div>
                                    <div><span class="text-sm text-gray-500">Unit Price:</span> <span class="font-medium">${{ number_format($order->plan->price, 2) }}</span></div>
                                    <div><span class="text-sm text-gray-500">RAM:</span> <span class="font-medium">{{ $order->plan->ram_gb }}GB</span></div>
                                    <div><span class="text-sm text-gray-500">Storage:</span> <span class="font-medium">{{ $order->plan->storage_gb }}GB</span></div>
                                    <div><span class="text-sm text-gray-500">Max Players:</span> <span class="font-medium">{{ $order->plan->max_players }}</span></div>
                                </div>
                            </div>
                        @endif

                        <!-- Payment Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Payment Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><span class="text-sm text-gray-500">Created:</span> <span class="font-medium">{{ $order->created_at->format('M j, Y g:i A') }}</span></div>
                                <div><span class="text-sm text-gray-500">Due Date:</span> <span class="font-medium">{{ $order->due_date ? Carbon\Carbon::parse($order->due_date)->format('M j, Y') : 'Not set' }}</span></div>
                                @if($order->paid_at)
                                    <div><span class="text-sm text-gray-500">Paid At:</span> <span class="font-medium">{{ Carbon\Carbon::parse($order->paid_at)->format('M j, Y g:i A') }}</span></div>
                                @endif
                                @if($order->refund_amount)
                                    <div><span class="text-sm text-gray-500">Refunded:</span> <span class="font-medium text-red-600">${{ number_format($order->refund_amount, 2) }}</span></div>
                                    <div><span class="text-sm text-gray-500">Refund Reason:</span> <span class="font-medium">{{ $order->refund_reason }}</span></div>
                                    <div><span class="text-sm text-gray-500">Refunded At:</span> <span class="font-medium">{{ $order->refunded_at ? Carbon\Carbon::parse($order->refunded_at)->format('M j, Y g:i A') : 'N/A' }}</span></div>
                                @endif
                            </div>
                        </div>

                        @if($order->notes)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Notes</h4>
                                <p class="text-gray-700">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button wire:click="closeModal" class="btn-secondary">Close</button>
                        <button wire:click="generatePDF({{ $order->id }})" class="btn-primary">Download PDF</button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Refund Modal -->
    @if($showRefundModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-md">
                <div class="modal-header">
                    <h3 class="text-lg font-medium text-red-600">Process Refund</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="processRefund" class="modal-body space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Refund Amount</label>
                        <input type="number" wire:model="refund_amount" class="input-field" step="0.01" min="0.01">
                        @error('refund_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Refund Reason</label>
                        <textarea wire:model="refund_reason" class="input-field" rows="3" placeholder="Reason for refund..."></textarea>
                        @error('refund_reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-md p-3">
                        <p class="text-sm text-red-600">
                            <strong>Warning:</strong> This action cannot be undone. The customer will be notified of the refund.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-danger">Process Refund</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
