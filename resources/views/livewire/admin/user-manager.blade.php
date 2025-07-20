<div class="modern-card">
    <!-- Header -->
    <div class="modern-card-header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">User Management</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Advanced user management and analytics</p>
            </div>
            <button wire:click="showCreateForm" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Create User
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
                        placeholder="Search users..." 
                        class="input-field w-64 pl-10"
                    >
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                
                <select wire:model.live="filterRole" class="input-field">
                    <option value="">All Roles</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                
                <select wire:model.live="filterStatus" class="input-field">
                    <option value="">All Status</option>
                    <option value="verified">Verified</option>
                    <option value="unverified">Unverified</option>
                </select>
            </div>
        </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')" class="sortable-header">
                        Name
                        @if($sortField === 'name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th wire:click="sortBy('email')" class="sortable-header">
                        Email
                        @if($sortField === 'email')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Stats
                    </th>
                    <th wire:click="sortBy('created_at')" class="sortable-header">
                        Joined
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
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button 
                                wire:click="toggleUserVerification({{ $user->id }})"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                            >
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $user->orders_count ?? 0 }} orders</div>
                            <div>{{ $user->servers_count ?? 0 }} servers</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button 
                                    wire:click="showEditForm({{ $user->id }})"
                                    class="text-blue-600 hover:text-blue-900"
                                >
                                    Edit
                                </button>
                                @if($user->role !== 'admin')
                                    <button 
                                        wire:click="impersonateUser({{ $user->id }})"
                                        class="text-green-600 hover:text-green-900"
                                    >
                                        Login As
                                    </button>
                                @endif
                                <button 
                                    wire:click="showDeleteForm({{ $user->id }})"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Create User Modal -->
    @if($showCreateModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-lg">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Create New User</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="createUser" class="modal-body space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" wire:model="name" class="input-field" placeholder="John Doe">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" wire:model="email" class="input-field" placeholder="john@example.com">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" wire:model="password" class="input-field" placeholder="Password">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" class="input-field" placeholder="Confirm Password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select wire:model="role" class="input-field">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_verified" id="is_verified" class="rounded">
                        <label for="is_verified" class="ml-2 text-sm text-gray-700">Email is verified</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit User Modal -->
    @if($showEditModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-lg">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Edit User</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="updateUser" class="modal-body space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" wire:model="name" class="input-field">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" wire:model="email" class="input-field">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                        <input type="password" wire:model="password" class="input-field" placeholder="Leave blank to keep current password">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" wire:model="password_confirmation" class="input-field" placeholder="Confirm new password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select wire:model="role" class="input-field">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_verified" id="edit_is_verified" class="rounded">
                        <label for="edit_is_verified" class="ml-2 text-sm text-gray-700">Email is verified</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Update User</button>
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
                    <h3 class="text-lg font-medium text-red-600">Delete User</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p class="text-gray-600">Are you sure you want to delete this user? This action cannot be undone.</p>
                    <p class="text-sm text-red-600 mt-2">Note: Users with active orders or servers cannot be deleted.</p>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-secondary">Cancel</button>
                    <button wire:click="deleteUser" class="btn-danger">Delete User</button>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
