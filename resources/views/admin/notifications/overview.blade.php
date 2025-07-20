@php
    $pageTitle = 'Notification Management Overview';
@endphp

<x-modern-layout title="Notification Management">
    <!-- Page Header -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h1>Notification Management</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and send notifications to users</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Send Notification
                </a>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-chart-bar"></i>
                </div>
                Notification Overview
            </div>
        </div>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Monitor and manage all system notifications from this central dashboard.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-blue-100 text-sm font-medium">Total Notifications</h4>
                            <p class="text-white text-3xl font-bold mt-2">{{ $totalNotifications }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bell text-xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-amber-100 text-sm font-medium">Unread Notifications</h4>
                            <p class="text-white text-3xl font-bold mt-2">{{ $totalUnread }}</p>
                        </div>
                        <div class="w-12 h-12 bg-amber-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-envelope text-xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-green-100 text-sm font-medium">Total Users</h4>
                            <p class="text-white text-3xl font-bold mt-2">{{ $totalUsers }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-purple-100 text-sm font-medium">Read Rate</h4>
                            <p class="text-white text-3xl font-bold mt-2">{{ number_format(($totalNotifications - $totalUnread) / max($totalNotifications, 1) * 100, 1) }}%</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-400 bg-opacity-30 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Notification Types -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notification Types</h3>
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
                                            <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
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
                                    <i class="fas fa-chart-pie text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">No Statistics Yet</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Notification type statistics will appear here once you start sending notifications.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Daily Activity Chart -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daily Activity</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Last 7 Days</span>
                        </div>
                        @if($dailyStats->count() > 0)
                            <div class="relative">
                                <canvas id="dailyChart" class="w-full" style="height: 300px;"></canvas>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                    <i class="fas fa-chart-line text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">No Activity Yet</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Send your first notification to see activity data here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Notifications</h3>
                        <a href="{{ route('admin.notifications.global') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            View All
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
                                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300">
                                                    {{ class_basename($notification->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($notification->read_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Read
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                                        <i class="fas fa-envelope mr-1"></i>
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
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <i class="fas fa-bell-slash text-2xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notifications sent yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start by sending your first notification.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
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
    </div>
</x-modern-layout>
