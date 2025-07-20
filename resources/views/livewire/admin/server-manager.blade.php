<div class="admin-card">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Server Management</h2>
        <button wire:click="showCreateForm" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Server
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

    <!-- Search and Filters -->
    <div class="flex flex-wrap items-center justify-between mb-6 gap-4">
        <div class="flex items-center space-x-4">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search servers..." 
                class="input-field w-64"
            >
            
            <select wire:model.live="filterStatus" class="input-field">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
                <option value="maintenance">Maintenance</option>
            </select>
            
            <select wire:model.live="filterUser" class="input-field">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Servers Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('name')" class="sortable-header">
                        Server Name
                        @if($sortField === 'name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Owner
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Plan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Connection
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th wire:click="sortBy('created_at')" class="sortable-header">
                        Created
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
                @forelse($servers as $server)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $server->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $server->server_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                    {{ strtoupper(substr($server->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $server->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $server->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $server->plan->name ?? 'No Plan' }}</div>
                            <div class="text-sm text-gray-500">
                                @if($server->plan)
                                    {{ $server->plan->ram_gb }}GB RAM • {{ $server->plan->max_players }} players
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $server->ip_address }}:{{ $server->port }}</div>
                            <div class="text-sm text-gray-500">{{ $server->minecraft_version }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $server->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($server->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                       ($server->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($server->status) }}
                                    <svg class="ml-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition class="absolute z-10 mt-1 w-32 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                                    <button wire:click="updateServerStatus({{ $server->id }}, 'active')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Active</button>
                                    <button wire:click="updateServerStatus({{ $server->id }}, 'inactive')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inactive</button>
                                    <button wire:click="updateServerStatus({{ $server->id }}, 'suspended')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Suspended</button>
                                    <button wire:click="updateServerStatus({{ $server->id }}, 'maintenance')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Maintenance</button>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ucfirst($server->server_type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $server->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                @if($server->status === 'active')
                                    <button wire:click="stopServer({{ $server->id }})" class="text-red-600 hover:text-red-900">Stop</button>
                                    <button wire:click="restartServer({{ $server->id }})" class="text-yellow-600 hover:text-yellow-900">Restart</button>
                                @else
                                    <button wire:click="startServer({{ $server->id }})" class="text-green-600 hover:text-green-900">Start</button>
                                @endif
                                <button wire:click="showViewForm({{ $server->id }})" class="text-blue-600 hover:text-blue-900">View</button>
                                <button wire:click="showDeleteForm({{ $server->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No servers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $servers->links() }}
    </div>

    <!-- Create Server Modal -->
    @if($showCreateModal)
        <div class="modal-overlay">
            <div class="modal-content max-w-2xl">
                <div class="modal-header">
                    <h3 class="text-lg font-medium">Create New Server</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <form wire:submit.prevent="createServer" class="modal-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Server Name</label>
                            <input type="text" wire:model="name" class="input-field" placeholder="My Minecraft Server">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                            <select wire:model="user_id" class="input-field">
                                <option value="">Select Owner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                            <select wire:model="plan_id" class="input-field">
                                <option value="">Select Plan</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->plan_id }}">{{ $order->plan->name ?? 'Unknown Plan' }} (Order #{{ $order->id }})</option>
                                @endforeach
                            </select>
                            @error('plan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order (Optional)</label>
                            <select wire:model="order_id" class="input-field">
                                <option value="">Select Order</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}">Order #{{ $order->id }} - {{ $order->user->name ?? 'Unknown' }}</option>
                                @endforeach
                            </select>
                            @error('order_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                            <input type="text" wire:model="ip_address" class="input-field" placeholder="127.0.0.1">
                            @error('ip_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                            <input type="number" wire:model="port" class="input-field" placeholder="25565">
                            @error('port') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model="status" class="input-field">
                                <option value="inactive">Inactive</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minecraft Version</label>
                            <select wire:model="minecraft_version" class="input-field">
                                <option value="1.20.1">1.20.1</option>
                                <option value="1.19.4">1.19.4</option>
                                <option value="1.18.2">1.18.2</option>
                                <option value="1.17.1">1.17.1</option>
                                <option value="1.16.5">1.16.5</option>
                            </select>
                            @error('minecraft_version') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Server Type</label>
                            <select wire:model="server_type" class="input-field">
                                <option value="vanilla">Vanilla</option>
                                <option value="bukkit">Bukkit</option>
                                <option value="spigot">Spigot</option>
                                <option value="paper">Paper</option>
                                <option value="forge">Forge</option>
                                <option value="fabric">Fabric</option>
                                <option value="modded">Modded</option>
                            </select>
                            @error('server_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Server ID (Optional)</label>
                        <input type="text" wire:model="server_id" class="input-field" placeholder="Auto-generated if empty">
                        @error('server_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Create Server</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- View Server Modal -->
    @if($showViewModal)
        @php
            $server = \App\Models\Server::with(['user', 'plan', 'order'])->find($serverId);
        @endphp
        @if($server)
            <div class="modal-overlay">
                <div class="modal-content max-w-3xl">
                    <div class="modal-header">
                        <h3 class="text-lg font-medium">Server Details: {{ $server->name }}</h3>
                        <button wire:click="closeModal" class="modal-close">&times;</button>
                    </div>
                    
                    <div class="modal-body space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Server Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Server Information</h4>
                                <div class="space-y-2">
                                    <div><span class="text-sm text-gray-500">Name:</span> <span class="font-medium">{{ $server->name }}</span></div>
                                    <div><span class="text-sm text-gray-500">Server ID:</span> <span class="font-medium">{{ $server->server_id ?? 'N/A' }}</span></div>
                                    <div><span class="text-sm text-gray-500">Status:</span> 
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $server->status === 'active' ? 'bg-green-100 text-green-800' : 
                                               ($server->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                               ($server->status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst($server->status) }}
                                        </span>
                                    </div>
                                    <div><span class="text-sm text-gray-500">Type:</span> <span class="font-medium">{{ ucfirst($server->server_type) }}</span></div>
                                    <div><span class="text-sm text-gray-500">Version:</span> <span class="font-medium">{{ $server->minecraft_version }}</span></div>
                                </div>
                            </div>

                            <!-- Connection Info -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Connection Details</h4>
                                <div class="space-y-2">
                                    <div><span class="text-sm text-gray-500">IP Address:</span> <span class="font-medium">{{ $server->ip_address }}</span></div>
                                    <div><span class="text-sm text-gray-500">Port:</span> <span class="font-medium">{{ $server->port }}</span></div>
                                    <div><span class="text-sm text-gray-500">Full Address:</span> <span class="font-medium">{{ $server->ip_address }}:{{ $server->port }}</span></div>
                                    <div><span class="text-sm text-gray-500">Last Online:</span> <span class="font-medium">{{ $server->last_online ? $server->last_online->format('M j, Y g:i A') : 'Never' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Owner Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><span class="text-sm text-gray-500">Owner:</span> <span class="font-medium">{{ $server->user->name ?? 'Unknown' }}</span></div>
                                <div><span class="text-sm text-gray-500">Email:</span> <span class="font-medium">{{ $server->user->email ?? 'N/A' }}</span></div>
                            </div>
                        </div>

                        <!-- Plan & Order Info -->
                        @if($server->plan || $server->order)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Plan & Order Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($server->plan)
                                        <div><span class="text-sm text-gray-500">Plan:</span> <span class="font-medium">{{ $server->plan->name }}</span></div>
                                        <div><span class="text-sm text-gray-500">RAM:</span> <span class="font-medium">{{ $server->plan->ram_gb }}GB</span></div>
                                        <div><span class="text-sm text-gray-500">Storage:</span> <span class="font-medium">{{ $server->plan->storage_gb }}GB</span></div>
                                        <div><span class="text-sm text-gray-500">Max Players:</span> <span class="font-medium">{{ $server->plan->max_players }}</span></div>
                                    @endif
                                    @if($server->order)
                                        <div><span class="text-sm text-gray-500">Order ID:</span> <span class="font-medium">#{{ $server->order->id }}</span></div>
                                        <div><span class="text-sm text-gray-500">Order Status:</span> <span class="font-medium">{{ ucfirst($server->order->status) }}</span></div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Timestamps</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><span class="text-sm text-gray-500">Created:</span> <span class="font-medium">{{ $server->created_at->format('M j, Y g:i A') }}</span></div>
                                <div><span class="text-sm text-gray-500">Last Updated:</span> <span class="font-medium">{{ $server->updated_at->format('M j, Y g:i A') }}</span></div>
                            </div>
                        </div>
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
                    <h3 class="text-lg font-medium text-red-600">Delete Server</h3>
                    <button wire:click="closeModal" class="modal-close">&times;</button>
                </div>
                
                <div class="modal-body">
                    <p class="text-gray-600">Are you sure you want to delete this server? This action cannot be undone.</p>
                    <p class="text-sm text-red-600 mt-2">Warning: All server data will be permanently lost!</p>
                </div>

                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn-secondary">Cancel</button>
                    <button wire:click="deleteServer" class="btn-danger">Delete Server</button>
                </div>
            </div>
        </div>
    @endif
</div>
