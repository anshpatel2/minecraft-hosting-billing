@php
    $pageTitle = 'Notification Management Overview';
@endphp

<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notification Management Overview') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg admin-card">
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Notification Management</h1>
                            <p class="text-gray-600 dark:text-gray-400">Monitor and manage all system notifications from this central dashboard.</p>
                        </div>
                        <div class="hidden md:block">
                            <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Send Notification
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
                        <p class="text-white text-2xl font-bold">{{ $totalNotifications }}</p>
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
                        <p class="text-white text-2xl font-bold">{{ $totalUnread }}</p>
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
                        <h4 class="text-green-100">Total Users</h4>
                        <p class="text-white text-2xl font-bold">{{ $totalUsers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="stats-card bg-gradient-to-br from-purple-500 to-indigo-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-purple-100">Read Rate</h4>
                        <p class="text-white text-2xl font-bold">{{ number_format(($totalNotifications - $totalUnread) / max($totalNotifications, 1) * 100, 1) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Notification Types -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg admin-card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notification Types</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Distribution</span>
                    </div>
                    @if($notificationStats->count() > 0)
                        <div class="space-y-4">
                            @php
                                $total = $notificationStats->sum('count');
                                $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500'];
                            @endphp
                            @foreach($notificationStats as $index => $stat)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 {{ $colors[$index % count($colors)] }} rounded-full"></div>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ class_basename($stat->type) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 w-20">
                                            <div class="{{ $colors[$index % count($colors)] }} h-2 rounded-full" style="width: {{ $total > 0 ? ($stat->count / $total) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 min-w-[3rem] justify-center">
                                            {{ $stat->count }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">No Statistics Yet</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Notification type statistics will appear here once you start sending notifications.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daily Activity Chart -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg admin-card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daily Activity</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Last 7 Days</span>
                    </div>
                    @if($dailyStats->count() > 0)
                        <div class="relative">
                            <canvas id="dailyChart" class="w-full" style="height: 300px;"></canvas>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">No Activity Yet</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Send your first notification to see activity data here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Notifications -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg admin-card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Notifications</h3>
                    <a href="{{ route('admin.notifications.global') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        View All
                        <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7l-10 10"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800">
                @if($recentNotifications->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Recipient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sent</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($recentNotifications as $notification)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                    {{ substr($notification->notifiable->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $notification->notifiable->name ?? 'Unknown User' }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $notification->notifiable->email ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $notification->data['title'] ?? 'No Title' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($notification->data['message'] ?? '', 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                {{ class_basename($notification->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($notification->read_at)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Read
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                    </svg>
                                                    Unread
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <div>{{ $notification->created_at->format('M j, Y') }}</div>
                                            <div class="text-xs">{{ $notification->created_at->format('g:i A') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5l-5-5h5V12h5v5z M9 3H4l5-5l5 5H9v5H4V3z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notifications sent yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by sending your first notification.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Send Notification
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for statistics -->
@if($dailyStats->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dailyChart');
    if (!ctx) return;
    
    const dailyData = @json($dailyStats);
    
    // Check current theme
    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
    
    // Theme-aware colors
    const gridColor = isDarkMode ? 'rgba(107, 114, 128, 0.2)' : 'rgba(107, 114, 128, 0.1)';
    const borderColor = isDarkMode ? 'rgba(107, 114, 128, 0.3)' : 'rgba(107, 114, 128, 0.2)';
    const tickColor = isDarkMode ? '#9CA3AF' : '#6B7280';
    const tooltipBg = isDarkMode ? 'rgba(17, 24, 39, 0.95)' : 'rgba(17, 24, 39, 0.9)';
    
    // Prepare data for the last 7 days, filling missing days with 0
    const last7Days = [];
    const counts = [];
    
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        
        const found = dailyData.find(item => item.date === dateStr);
        last7Days.push(date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' }));
        counts.push(found ? found.count : 0);
    }
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: last7Days,
            datasets: [{
                label: 'Notifications Sent',
                data: counts,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#1D4ED8',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            const count = context.parsed.y;
                            return count === 1 ? '1 notification sent' : `${count} notifications sent`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: tickColor,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: gridColor,
                        borderColor: borderColor
                    }
                },
                x: {
                    ticks: {
                        color: tickColor,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        color: borderColor
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
    
    // Listen for theme changes and update chart
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                const newGridColor = isDark ? 'rgba(107, 114, 128, 0.2)' : 'rgba(107, 114, 128, 0.1)';
                const newBorderColor = isDark ? 'rgba(107, 114, 128, 0.3)' : 'rgba(107, 114, 128, 0.2)';
                const newTickColor = isDark ? '#9CA3AF' : '#6B7280';
                const newTooltipBg = isDark ? 'rgba(17, 24, 39, 0.95)' : 'rgba(17, 24, 39, 0.9)';
                
                chart.options.scales.y.ticks.color = newTickColor;
                chart.options.scales.y.grid.color = newGridColor;
                chart.options.scales.y.grid.borderColor = newBorderColor;
                chart.options.scales.x.ticks.color = newTickColor;
                chart.options.scales.x.border.color = newBorderColor;
                chart.options.plugins.tooltip.backgroundColor = newTooltipBg;
                chart.update();
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
});
</script>
@endif
</x-admin-layout>
