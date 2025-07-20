<x-modern-layout title="System Health Monitor">
    <!-- Page Header -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>
                    <h1>System Health Monitor</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Real-time monitoring of system performance and health</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="refreshMetrics()" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
                <button onclick="exportReport()" class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- System Status Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">System Uptime</span>
                <div class="stat-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">99.98%</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                24 days, 6 hours
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">CPU Usage</span>
                <div class="stat-icon" style="background: var(--gradient-warning);">
                    <i class="fas fa-microchip"></i>
                </div>
            </div>
            <div class="stat-value">67%</div>
            <div class="stat-change">
                <i class="fas fa-info-circle"></i>
                Normal load
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Memory Usage</span>
                <div class="stat-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-memory"></i>
                </div>
            </div>
            <div class="stat-value">8.2GB</div>
            <div class="stat-change">
                <i class="fas fa-chart-line"></i>
                of 16GB used
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Active Connections</span>
                <div class="stat-icon" style="background: var(--gradient-secondary);">
                    <i class="fas fa-network-wired"></i>
                </div>
            </div>
            <div class="stat-value">1,247</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +23 in last hour
            </div>
        </div>
    </div>

    <!-- System Health Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance Metrics -->
        <div class="modern-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-icon" style="background: var(--gradient-primary);">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Performance Metrics
                </div>
            </div>

            <div class="space-y-6">
                <!-- CPU Usage Bar -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">CPU Usage</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">67%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full transition-all duration-500" style="width: 67%"></div>
                    </div>
                </div>

                <!-- Memory Usage Bar -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Memory Usage</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">51%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-400 to-indigo-500 h-3 rounded-full transition-all duration-500" style="width: 51%"></div>
                    </div>
                </div>

                <!-- Disk Usage Bar -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Disk Usage</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">78%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-500" style="width: 78%"></div>
                    </div>
                </div>

                <!-- Network I/O Bar -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Network I/O</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">34%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-400 to-pink-500 h-3 rounded-full transition-all duration-500" style="width: 34%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Status -->
        <div class="modern-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-icon" style="background: var(--gradient-success);">
                        <i class="fas fa-server"></i>
                    </div>
                    Service Status
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Web Server</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Apache/Nginx</p>
                        </div>
                    </div>
                    <span class="text-green-600 dark:text-green-400 font-semibold">Online</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Database</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">MySQL 8.0</p>
                        </div>
                    </div>
                    <span class="text-green-600 dark:text-green-400 font-semibold">Online</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Redis Cache</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">v7.0.8</p>
                        </div>
                    </div>
                    <span class="text-green-600 dark:text-green-400 font-semibold">Online</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Queue Worker</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Laravel Queue</p>
                        </div>
                    </div>
                    <span class="text-yellow-600 dark:text-yellow-400 font-semibold">Slow</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Mail Service</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">SMTP</p>
                        </div>
                    </div>
                    <span class="text-green-600 dark:text-green-400 font-semibold">Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Performance Chart -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-chart-area"></i>
                </div>
                Live Performance Monitor
            </div>
            <div class="flex gap-3">
                <button id="pauseBtn" onclick="toggleMonitoring()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-pause"></i>
                    Pause
                </button>
                <select class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800">
                    <option>Last 1 hour</option>
                    <option>Last 6 hours</option>
                    <option>Last 24 hours</option>
                </select>
            </div>
        </div>
        
        <div class="h-80">
            <canvas id="performanceChart" width="400" height="300"></canvas>
        </div>
    </div>

    <!-- System Logs -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon" style="background: var(--gradient-secondary);">
                    <i class="fas fa-list-alt"></i>
                </div>
                Recent System Logs
            </div>
            <div class="flex gap-3">
                <select class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800">
                    <option>All Levels</option>
                    <option>Error</option>
                    <option>Warning</option>
                    <option>Info</option>
                </select>
                <button onclick="clearLogs()" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                    Clear Logs
                </button>
            </div>
        </div>

        <div class="space-y-3 max-h-96 overflow-y-auto">
            <div class="flex items-start gap-4 p-4 bg-red-50 dark:bg-red-900 rounded-lg border border-red-200 dark:border-red-700">
                <div class="w-3 h-3 bg-red-500 rounded-full mt-2 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-red-800 dark:text-red-200">ERROR</span>
                        <span class="text-xs text-red-600 dark:text-red-400">2 min ago</span>
                    </div>
                    <p class="text-sm text-red-700 dark:text-red-300">Database connection timeout in UserController::index()</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">File: /app/Http/Controllers/UserController.php:42</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-yellow-800 dark:text-yellow-200">WARNING</span>
                        <span class="text-xs text-yellow-600 dark:text-yellow-400">5 min ago</span>
                    </div>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">High memory usage detected - 85% of available RAM in use</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                <div class="w-3 h-3 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-blue-800 dark:text-blue-200">INFO</span>
                        <span class="text-xs text-blue-600 dark:text-blue-400">8 min ago</span>
                    </div>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Queue worker processed 127 jobs successfully</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-green-50 dark:bg-green-900 rounded-lg border border-green-200 dark:border-green-700">
                <div class="w-3 h-3 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-green-800 dark:text-green-200">SUCCESS</span>
                        <span class="text-xs text-green-600 dark:text-green-400">12 min ago</span>
                    </div>
                    <p class="text-sm text-green-700 dark:text-green-300">System backup completed successfully - 2.4GB archived</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="w-3 h-3 bg-gray-500 rounded-full mt-2 flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-gray-800 dark:text-gray-200">DEBUG</span>
                        <span class="text-xs text-gray-600 dark:text-gray-400">15 min ago</span>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">User authentication check completed for session ID: abc123</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let isMonitoring = true;
        let performanceChart;

        // Initialize Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'CPU Usage',
                        data: [],
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Memory Usage',
                        data: [],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Network I/O',
                        data: [],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(156, 163, 175, 0.2)'
                        }
                    }
                }
            }
        });

        // Simulate real-time data
        function updateChart() {
            if (!isMonitoring) return;

            const now = new Date();
            const timeLabel = now.getHours().toString().padStart(2, '0') + ':' + 
                            now.getMinutes().toString().padStart(2, '0') + ':' + 
                            now.getSeconds().toString().padStart(2, '0');

            // Generate random data (in real app, this would come from actual metrics)
            const cpuUsage = Math.random() * 40 + 50; // 50-90%
            const memoryUsage = Math.random() * 30 + 40; // 40-70%
            const networkIO = Math.random() * 50 + 20; // 20-70%

            // Add new data point
            performanceChart.data.labels.push(timeLabel);
            performanceChart.data.datasets[0].data.push(cpuUsage);
            performanceChart.data.datasets[1].data.push(memoryUsage);
            performanceChart.data.datasets[2].data.push(networkIO);

            // Keep only last 20 data points
            if (performanceChart.data.labels.length > 20) {
                performanceChart.data.labels.shift();
                performanceChart.data.datasets.forEach(dataset => {
                    dataset.data.shift();
                });
            }

            performanceChart.update('none');
        }

        // Update chart every 3 seconds
        setInterval(updateChart, 3000);

        function toggleMonitoring() {
            isMonitoring = !isMonitoring;
            const btn = document.getElementById('pauseBtn');
            if (isMonitoring) {
                btn.innerHTML = '<i class="fas fa-pause"></i> Pause';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-secondary');
            } else {
                btn.innerHTML = '<i class="fas fa-play"></i> Resume';
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-success');
            }
        }

        function refreshMetrics() {
            // Simulate refresh with loading animation
            const btn = event.target.closest('button');
            const icon = btn.querySelector('i');
            icon.classList.add('fa-spin');
            
            setTimeout(() => {
                icon.classList.remove('fa-spin');
                // In real app, would reload actual metrics
                alert('Metrics refreshed successfully!');
            }, 1500);
        }

        function exportReport() {
            alert('Exporting system health report... (Feature coming soon!)');
        }

        function clearLogs() {
            if (confirm('Are you sure you want to clear all system logs? This action cannot be undone.')) {
                alert('Logs cleared successfully!');
            }
        }

        // Initialize chart with some initial data
        setTimeout(() => {
            for (let i = 0; i < 10; i++) {
                updateChart();
            }
        }, 1000);
    </script>
    @endpush
</x-modern-layout>
