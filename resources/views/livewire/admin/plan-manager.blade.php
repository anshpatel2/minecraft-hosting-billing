<div class="modern-card">
    <!-- Header -->
    <div class="modern-card-header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Plan Management</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage hosting plans and pricing</p>
            </div>
            <button wire:click="showCreateForm" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Create Plan
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
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="search" 
                        placeholder="Search plans..." 
                        class="input-field w-64 pl-10"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            </div>

        <!-- Plans Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th wire:click="sortBy('name')" class="sortable-header cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-600">
                                Name
                                @if($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Specs
                    </th>
                    <th wire:click="sortBy('price')" class="sortable-header">
                        Price
                        @if($sortField === 'price')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Orders
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($plans as $plan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($plan->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="space-y-1">
                                <div>{{ $plan->ram_gb }}GB RAM</div>
                                <div>{{ $plan->storage_gb }}GB Storage</div>
                                <div>{{ $plan->max_players }} Players</div>
                                <div>{{ ucfirst($plan->billing_cycle ?? 'monthly') }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ number_format($plan->price, 2) }}</div>
                            <div class="text-sm text-gray-500">per month</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button 
                                wire:click="togglePlanStatus({{ $plan->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                            >
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $plan->orders_count ?? 0 }} total</div>
                            <div>{{ $plan->active_orders_count ?? 0 }} active</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button 
                                    wire:click="showEditForm({{ $plan->id }})"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="showDeleteForm({{ $plan->id }})"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No plans found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $plans->links() }}
    </div>

    <!-- Create Plan Modal -->
    @if($showCreateModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-2xl">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Create New Plan</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="createPlan" class="modal-body space-y-4">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                            <input type="text" wire:model="name" class="input-field" placeholder="e.g., Starter Plan">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price ($/month)</label>
                            <input type="number" step="0.01" wire:model="price" class="input-field" placeholder="9.99">
                            @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="input-field" placeholder="Plan description..."></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Server Specifications -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RAM (GB)</label>
                            <input type="number" wire:model="ram_gb" class="input-field" placeholder="2">
                            @error('ram_gb') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Storage (GB)</label>
                            <input type="number" wire:model="storage_gb" class="input-field" placeholder="10">
                            @error('storage_gb') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Players</label>
                            <input type="number" wire:model="max_players" class="input-field" placeholder="10">
                            @error('max_players') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select wire:model="billing_cycle" class="input-field">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="annually">Annually</option>
                        </select>
                        @error('billing_cycle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                        @foreach($features as $index => $feature)
                            <div class="flex items-center space-x-2 mb-2">
                                <input 
                                    type="text" 
                                    wire:model="features.{{ $index }}" 
                                    class="input-field flex-1" 
                                    placeholder="Feature description"
                                >
                                <button 
                                    type="button" 
                                    wire:click="removeFeature({{ $index }})" 
                                    class="btn-danger p-2"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addFeature" class="btn-secondary text-sm">
                            Add Feature
                        </button>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Plan is active</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Plan Modal -->
    @if($showEditModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-2xl">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Edit Plan</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="updatePlan" class="modal-body space-y-4">
                    <!-- Same form fields as create modal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                            <input type="text" wire:model="name" class="input-field">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price ($/month)</label>
                            <input type="number" step="0.01" wire:model="price" class="input-field">
                            @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="input-field"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RAM (GB)</label>
                            <input type="number" wire:model="ram_gb" class="input-field">
                            @error('ram_gb') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Storage (GB)</label>
                            <input type="number" wire:model="storage_gb" class="input-field">
                            @error('storage_gb') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Players</label>
                            <input type="number" wire:model="max_players" class="input-field">
                            @error('max_players') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select wire:model="billing_cycle" class="input-field">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="annually">Annually</option>
                        </select>
                        @error('billing_cycle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                        @foreach($features as $index => $feature)
                            <div class="flex items-center space-x-2 mb-2">
                                <input 
                                    type="text" 
                                    wire:model="features.{{ $index }}" 
                                    class="input-field flex-1"
                                >
                                <button 
                                    type="button" 
                                    wire:click="removeFeature({{ $index }})" 
                                    class="btn-danger p-2"
                                >
                                    ×
                                </button>
                            </div>
                        @endforeach
                        <button type="button" wire:click="addFeature" class="btn-secondary text-sm">
                            Add Feature
                        </button>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="edit_is_active" class="rounded">
                        <label for="edit_is_active" class="ml-2 text-sm text-gray-700">Plan is active</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-md">
                <div class="modal-header">
                    <h3 class="text-lg font-medium text-red-600">Delete Plan</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p class="text-gray-600">Are you sure you want to delete this plan? This action cannot be undone.</p>
                    <p class="text-sm text-red-600 mt-2">Note: Plans with active orders cannot be deleted.</p>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-secondary">Cancel</button>
                    <button wire:click="deletePlan" class="btn-danger">Delete Plan</button>
                </div>
            </div>
        </div>
    @endif
</div>
