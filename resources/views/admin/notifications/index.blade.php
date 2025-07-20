@php
    $pageTitle = 'Admin Notifications';
@endphp

<x-modern-layout title="Admin Notifications">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">Admin Notifications</h1>
                <p class="text-indigo-100 text-lg">Manage system notifications and user communications</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div x-data="notificationManager()" class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Actions Header -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Notification Management</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">View and manage all system notifications</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.notifications.create') }}" class="btn-primary">
                                <i class="fas fa-plus mr-2"></i>Send Notification
                            </a>
                            <a href="{{ route('admin.notifications.overview') }}" class="btn-secondary">
                                <i class="fas fa-chart-bar mr-2"></i>Overview
                            </a>
                        </div>
                    </div>
                </div>

                <div class="modern-card-body">
                    <!-- Filters and Search -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <form method="GET" class="flex items-center space-x-4">
                                <div class="relative">
                                    <input type="text" name="search" 
                                           class="input-field w-64 pl-10" 
                                           placeholder="Search notifications..." 
                                           value="{{ request('search') }}">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                                
                                <select name="filter" class="input-field">
                                    <option value="" {{ request('filter') === '' ? 'selected' : '' }}>All</option>
                                    <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>Unread</option>
                                    <option value="read" {{ request('filter') === 'read' ? 'selected' : '' }}>Read</option>
                                </select>
                                
                                <button type="submit" class="btn-secondary text-xs">
                                    <i class="fas fa-search mr-1"></i>Search
                                </button>
                                <a href="{{ route('admin.notifications.index') }}" class="btn-secondary text-xs">
                                    <i class="fas fa-times mr-1"></i>Clear
                                </a>
                            </form>
                        </div>

                        <!-- Bulk Actions -->
                        <div x-show="selectedNotifications.length > 0" x-transition class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400" x-text="selectedNotifications.length + ' selected'"></span>
                            <form method="POST" action="{{ route('admin.notifications.bulk-action') }}" x-ref="bulkForm" class="inline flex space-x-2">
                                @csrf
                                <input type="hidden" name="notification_ids" :value="selectedNotifications.join(',')">
                                <input type="hidden" name="action" x-ref="bulkAction">
                                <button type="button" @click="bulkMarkAsRead()" class="btn-success text-xs">
                                    <i class="fas fa-check mr-1"></i>Mark as Read
                                </button>
                                <button type="button" @click="bulkDelete()" class="btn-danger text-xs">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($notifications as $notification)
                        <div class="relative group hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->read_at ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/20' }} transition-colors">
                            <div class="flex items-start space-x-4 p-6">
                                <!-- Checkbox -->
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           :value="'{{ $notification->id }}'" 
                                           x-model="selectedNotifications"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded">
                                </div>

                                <!-- Notification Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 {{ $notification->read_at ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-500' }} rounded-full flex items-center justify-center">
                                        @if($notification->read_at)
                                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5V12h5v5z M9 3H4l5-5l5 5H9v5H4V3z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Notification Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 {{ $notification->read_at ? '' : 'font-semibold' }}">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </h4>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $notification->data['message'] ?? 'No message content' }}
                                            </p>
                                            <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                <span>{{ $notification->created_at->format('M j, Y g:i A') }}</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notification->read_at ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' }}">
                                                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                    {{ class_basename($notification->type) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @if(!$notification->read_at)
                                                <form method="POST" action="{{ route('admin.notifications.mark-read', $notification->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                                        Mark as Read
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if(isset($notification->data['action_url']) && $notification->data['action_url'])
                                                <a href="{{ $notification->data['action_url'] }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 hover:bg-blue-200 dark:hover:bg-blue-900/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                   target="_blank">
                                                    {{ $notification->data['action_text'] ?? 'View' }}
                                                    <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7l-10 10"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                            
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-600">
                                                    <div class="py-1">
                                                        @if(!$notification->read_at)
                                                            <form method="POST" action="{{ route('admin.notifications.mark-read', $notification->id) }}">
                                                                @csrf
                                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Mark as Read</button>
                                                            </form>
                                                        @else
                                                            <span class="block w-full text-left px-4 py-2 text-sm text-gray-500 dark:text-gray-400">Already Read</span>
                                                        @endif
                                                        <form method="POST" action="{{ route('admin.notifications.delete', $notification->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this notification?')" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-600">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5V12h5v5z M9 3H4l5-5l5 5H9v5H4V3z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notifications</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if(request('search') || request('filter'))
                            No notifications match your current filters.
                        @else
                            You don't have any notifications yet.
                        @endif
                    </p>
                    @if(request('search') || request('filter'))
                        <div class="mt-6">
                            <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function notificationManager() {
    return {
        selectedNotifications: [],
        
        bulkMarkAsRead() {
            if (this.selectedNotifications.length === 0) {
                alert('Please select notifications first.');
                return;
            }
            
            this.$refs.bulkAction.value = 'mark_read';
            this.$refs.bulkForm.submit();
        },
        
        bulkDelete() {
            if (this.selectedNotifications.length === 0) {
                alert('Please select notifications first.');
                return;
            }
            
            if (confirm('Are you sure you want to delete the selected notifications?')) {
                this.$refs.bulkAction.value = 'delete';
                this.$refs.bulkForm.submit();
            }
        }
    }
}
</script>
    </div>
</x-modern-layout>
