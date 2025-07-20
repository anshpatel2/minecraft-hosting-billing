@php
    $pageTitle = 'All Notifications';
@endphp

<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Notifications') }}
        </h2>
    </x-slot>

<div x-data="globalNotifications()" class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg admin-card">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">All Notifications</h1>
                            <p class="text-gray-600 mt-1">System-wide notification overview and management</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Send Notification
                            </a>
                            <a href="{{ route('admin.notifications.overview') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                                </svg>
                                Overview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="dashboard-grid mb-8">
            <div class="stats-card bg-gradient-to-br from-blue-500 to-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-blue-100">Total Notifications</h4>
                        <p class="text-white text-2xl font-bold">{{ $totalCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5V12h5v5z M9 3H4l5-5l5 5H9v5H4V3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="stats-card bg-gradient-to-br from-amber-500 to-orange-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-amber-100">Unread Notifications</h4>
                        <p class="text-white text-2xl font-bold">{{ $unreadCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="stats-card bg-gradient-to-br from-green-500 to-emerald-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-green-100">Read Notifications</h4>
                        <p class="text-white text-2xl font-bold">{{ $readCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="stats-card bg-gradient-to-br from-purple-500 to-indigo-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-purple-100">Read Rate</h4>
                        <p class="text-white text-2xl font-bold">{{ number_format($readCount / max($totalCount, 1) * 100, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg admin-card">
                <div class="p-6">
                    <form method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Status:</label>
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="" {{ request('status') === '' ? 'selected' : '' }}>All</option>
                                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm font-medium text-gray-700">Type:</label>
                            <select name="type" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="" {{ request('type') === '' ? 'selected' : '' }}>All Types</option>
                                @foreach($notificationTypes as $type)
                                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                        {{ class_basename($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="search" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Search notifications..." value="{{ request('search') }}">
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            <a href="{{ route('admin.notifications.global') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifications Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg admin-card">
            @if($notifications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($notifications as $notification)
                                <tr class="hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                {{ substr($notification->notifiable->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $notification->notifiable->name ?? 'Unknown User' }}</div>
                                                <div class="text-sm text-gray-500">{{ $notification->notifiable->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'No Title' }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($notification->data['message'] ?? 'No message', 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ class_basename($notification->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($notification->read_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Read
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $notification->read_at->format('M j, g:i A') }}</div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                </svg>
                                                Unread
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $notification->created_at->format('M j, Y') }}</div>
                                        <div class="text-xs">{{ $notification->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button @click="showDetails('{{ $notification->id }}')" 
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            View
                                        </button>
                                        @if(isset($notification->data['action_url']) && $notification->data['action_url'])
                                            <a href="{{ $notification->data['action_url'] }}" 
                                               target="_blank"
                                               class="text-green-600 hover:text-green-900">
                                                {{ $notification->data['action_text'] ?? 'Action' }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $notifications->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5V12h5v5z M9 3H4l5-5l5 5H9v5H4V3z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['search', 'status', 'type']))
                            No notifications match your current filters.
                        @else
                            No notifications have been sent yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'type']))
                        <div class="mt-6">
                            <a href="{{ route('admin.notifications.global') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Notification Details Modal -->
    <div x-show="detailsModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" 
         style="display: none;">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="detailsModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.away="detailsModal = false"
                     class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5V12h5v5z M9 3H4l5-5l5 5H9v5H4V3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" x-text="selectedNotification.title">Notification Details</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" x-text="selectedNotification.message">Notification content...</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button type="button"
                                @click="detailsModal = false"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                            Close
                        </button>
                        <button type="button"
                                x-show="selectedNotification.action_url"
                                @click="window.open(selectedNotification.action_url, '_blank')"
                                class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                            <span x-text="selectedNotification.action_text || 'View'">View</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function globalNotifications() {
    return {
        detailsModal: false,
        selectedNotification: {},
        allNotifications: @json($notifications->items()),
        
        showDetails(notificationId) {
            const notification = this.allNotifications.find(n => n.id === notificationId);
            if (notification) {
                this.selectedNotification = {
                    title: notification.data.title || 'No Title',
                    message: notification.data.message || 'No message content',
                    action_url: notification.data.action_url || null,
                    action_text: notification.data.action_text || 'View'
                };
                this.detailsModal = true;
            }
        }
    }
}
</script>
@endsection
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalCount - $unreadCount }}</h4>
                            <p class="card-text">Read Notifications</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All System Notifications</h5>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Recipient</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Sent</th>
                                        <th>Read</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                        <tr class="{{ $notification->read_at ? '' : 'table-warning' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-2">
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                                            {{ substr($notification->notifiable->name ?? 'U', 0, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $notification->notifiable->name ?? 'Unknown User' }}</strong>
                                                        <small class="text-muted d-block">{{ $notification->notifiable->email ?? 'No Email' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $notification->data['title'] ?? 'No Title' }}</strong>
                                                <small class="text-muted d-block">{{ Str::limit($notification->data['message'] ?? '', 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ class_basename($notification->type) }}</span>
                                            </td>
                                            <td>
                                                @if($notification->read_at)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Read
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-envelope me-1"></i>Unread
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $notification->created_at->format('M j, Y') }}
                                                    <small class="text-muted d-block">{{ $notification->created_at->format('g:i A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($notification->read_at)
                                                    <div>
                                                        {{ $notification->read_at->format('M j, Y') }}
                                                        <small class="text-muted d-block">{{ $notification->read_at->format('g:i A') }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if($notification->data['url'])
                                                        <a href="{{ $notification->data['url'] }}" class="btn btn-outline-primary btn-sm" title="View Action">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @endif
                                                    <button class="btn btn-outline-info btn-sm" title="View Details" data-bs-toggle="modal" data-bs-target="#notificationModal{{ $notification->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications found</h5>
                            <p class="text-muted">No notifications have been sent yet.</p>
                            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                                Send First Notification
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Detail Modals -->
@foreach($notifications as $notification)
    <div class="modal fade" id="notificationModal{{ $notification->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notification Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Recipient:</strong></div>
                        <div class="col-sm-9">{{ $notification->notifiable->name ?? 'Unknown User' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Email:</strong></div>
                        <div class="col-sm-9">{{ $notification->notifiable->email ?? 'No Email' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Title:</strong></div>
                        <div class="col-sm-9">{{ $notification->data['title'] ?? 'No Title' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Message:</strong></div>
                        <div class="col-sm-9">{{ $notification->data['message'] ?? 'No Message' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Type:</strong></div>
                        <div class="col-sm-9">{{ class_basename($notification->type) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            @if($notification->read_at)
                                <span class="badge bg-success">Read</span>
                            @else
                                <span class="badge bg-warning">Unread</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Sent:</strong></div>
                        <div class="col-sm-9">{{ $notification->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    @if($notification->read_at)
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Read:</strong></div>
                            <div class="col-sm-9">{{ $notification->read_at->format('M j, Y g:i A') }}</div>
                        </div>
                    @endif
                    @if($notification->data['url'])
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Action URL:</strong></div>
                            <div class="col-sm-9">
                                <a href="{{ $notification->data['url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    Visit Link <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
</x-admin-layout>
