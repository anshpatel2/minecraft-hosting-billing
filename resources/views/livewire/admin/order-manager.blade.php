<div class="modern-card">
    <!-- Header -->
    <div class="modern-card-header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Order Management</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">View and manage customer orders</p>
            </div>
            <button wire:click="showCreateForm" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Create Order
            </button>
        </div>
    </div>

    <div class="modern-card-body">
        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-error mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Search and Filters -->
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search" 
                        placeholder="Search orders..." 
                        class="input-field w-64 pl-10"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                
                <select wire:model.live="filterStatus" class="input-field">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                
                <select wire:model.live="filterPlan" class="input-field">
                    <option value="">All Plans</option>
                    @foreach($allPlans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                    @endforeach
                </select>
        </div>

        <!-- Orders Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th wire:click="sortBy('id')" class="sortable-header cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600">
                                Order #
                                @if($sortField === 'id')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Plan
                            </th>
                            <th wire:click="sortBy('total_amount')" class="sortable-header cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600">
                                Amount
                                @if($sortField === 'total_amount')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th wire:click="sortBy('created_at')" class="sortable-header cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600">
                                Created
                                @if($sortField === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $order->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                            {{ strtoupper(substr($order->user->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->user->name ?? 'Unknown' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->plan->name ?? 'Unknown Plan' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($order->plan->price ?? 0, 2) }}/month</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${{ number_format($order->total_amount ?? $order->amount ?? 0, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($order->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($order->status === 'suspended') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                        <i class="fas fa-circle text-xs mr-1"></i>{{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col">
                                        <span>{{ $order->created_at->format('M j, Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ $order->created_at->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            wire:click="showViewForm({{ $order->id }})"
                                            class="btn-secondary text-xs"
                                            title="View Order"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                            <button @click="open = !open" class="btn-secondary text-xs" title="Change Status">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div x-show="open" x-transition class="absolute right-0 z-10 mt-1 w-32 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'pending')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <i class="fas fa-clock text-yellow-500 mr-2"></i>Pending
                                                </button>
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'active')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <i class="fas fa-check text-green-500 mr-2"></i>Active
                                                </button>
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'suspended')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <i class="fas fa-pause text-orange-500 mr-2"></i>Suspended
                                                </button>
                                                <button wire:click="updateOrderStatus({{ $order->id }}, 'cancelled')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <i class="fas fa-times text-red-500 mr-2"></i>Cancelled
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <button 
                                            wire:click="showDeleteForm({{ $order->id }})"
                                            class="btn-danger text-xs"
                                            title="Delete Order"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No orders found</h3>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Get started by creating your first order</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="bg-white dark:bg-gray-800 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Order Modal -->
    @if($showCreateModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-lg">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Create New Order</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="createOrder" class="modal-body space-y-4">
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
                        <select wire:model="plan_id" wire:change="updatedPlanId" class="input-field">
                            <option value="">Select Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ number_format($plan->price, 2) }}/month</option>
                            @endforeach
                        </select>
                        @error('plan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                        <input type="number" step="0.01" wire:model="total_amount" class="input-field" placeholder="0.00">
                        @error('total_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status" class="input-field">
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="suspended">Suspended</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea wire:model="notes" rows="3" class="input-field" placeholder="Order notes..."></textarea>
                        @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Create Order</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Order Modal -->
    @if($showViewModal)
        @php
            $order = \App\Models\Order::with(['user', 'plan'])->find($orderId);
        @endphp
        @if($order)
            <div class="modal-overlay">
                <div class="modal-content max-w-2xl">
                    <div class="modal-header">
                        <h3 class="text-lg font-medium">Order Details #{{ $order->id }}</h3>
                        <button wire:click="closeModal" class="modal-close">&times;</button>
                    </div>
                    
                    <div class="modal-body space-y-6">
                        <!-- Customer Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Customer Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Name:</span>
                                    <div class="font-medium">{{ $order->user->name }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Email:</span>
                                    <div class="font-medium">{{ $order->user->email }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Plan Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Plan:</span>
                                    <div class="font-medium">{{ $order->plan->name }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Plan Price:</span>
                                    <div class="font-medium">${{ number_format($order->plan->price, 2) }}/month</div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Order Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500">Total Amount:</span>
                                    <div class="font-medium">${{ number_format($order->total_amount, 2) }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <div class="font-medium">{{ ucfirst($order->status) }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Created:</span>
                                    <div class="font-medium">{{ $order->created_at->format('M j, Y g:i A') }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Updated:</span>
                                    <div class="font-medium">{{ $order->updated_at->format('M j, Y g:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($order->notes)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-2">Notes</h4>
                                <p class="text-gray-700">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button wire:click="closeModal" class="btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-md">
                <div class="modal-header">
                    <h3 class="text-lg font-medium text-red-600">Delete Order</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p class="text-gray-600">Are you sure you want to delete this order? This action cannot be undone.</p>
                    <p class="text-sm text-red-600 mt-2">Note: Active orders cannot be deleted.</p>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-secondary">Cancel</button>
                    <button wire:click="deleteOrder" class="btn-danger">Delete Order</button>
                </div>
            </div>
        </div>
    @endif
</div>
