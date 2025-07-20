<x-modern-layout title="My Servers">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-emerald-600 to-teal-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">My Servers</h1>
                <p class="text-green-100 text-lg">Manage your Minecraft servers</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Server Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stats-card bg-gradient-to-br from-green-500 to-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Servers</p>
                            <p class="text-3xl font-bold text-white">{{ auth()->user()->servers->where('status', 'active')->count() ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-server text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-blue-500 to-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Players</p>
                            <p class="text-3xl font-bold text-white">{{ auth()->user()->servers->sum('max_players') ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-users text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card bg-gradient-to-br from-purple-500 to-purple-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Monthly Cost</p>
                            <p class="text-3xl font-bold text-white">${{ auth()->user()->servers->sum('monthly_cost') ?? '0.00' }}</p>
                        </div>
                        <div class="p-3 bg-white/10 rounded-full">
                            <i class="fas fa-dollar-sign text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Server Management Section -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Server Management</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your Minecraft servers</p>
                        </div>
                        @can('create-servers')
                        <button class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Create New Server
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="modern-card-body">
                    @if(auth()->user()->servers && auth()->user()->servers->count() > 0)
                        <!-- Servers List -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @foreach(auth()->user()->servers as $server)
                            <div class="server-card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-lg transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-server text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $server->name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $server->plan->name ?? 'Basic Plan' }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                                        @if($server->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($server->status === 'stopped') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                        {{ ucfirst($server->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Players</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $server->current_players ?? 0 }}/{{ $server->max_players ?? 20 }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Uptime</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $server->uptime ?? '0h 0m' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Memory</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $server->memory_usage ?? '0' }}MB/{{ $server->max_memory ?? '1024' }}MB</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Version</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $server->minecraft_version ?? '1.20.1' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    @if($server->status === 'active')
                                        <button class="btn-secondary text-xs flex-1">
                                            <i class="fas fa-stop mr-1"></i>Stop
                                        </button>
                                        <button class="btn-secondary text-xs flex-1">
                                            <i class="fas fa-redo mr-1"></i>Restart
                                        </button>
                                    @else
                                        <button class="btn-primary text-xs flex-1">
                                            <i class="fas fa-play mr-1"></i>Start
                                        </button>
                                    @endif
                                    <button class="btn-secondary text-xs">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-server text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Servers Yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                You haven't created any Minecraft servers yet. Create your first server to get started with hosting.
                            </p>
                            @can('create-servers')
                            <button class="btn-primary">
                                <i class="fas fa-plus mr-2"></i>Create Your First Server
                            </button>
                            @else
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 inline-block">
                                <p class="text-blue-800 dark:text-blue-200 text-sm">
                                    Contact your administrator to create servers for your account.
                                </p>
                            </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-modern-layout>
