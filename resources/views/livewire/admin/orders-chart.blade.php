<div class="admin-card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Orders Over Time</h3>
        <div class="flex items-center space-x-2">
            <label for="period" class="text-sm font-medium text-gray-700">Period:</label>
            <select wire:model.live="period" id="period" class="rounded-md border-gray-300 text-sm">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
            </select>
        </div>
    </div>

    @if($chartData && isset($chartData['labels']) && count($chartData['labels']) > 0)
    <div class="chart-container" style="position: relative; height: 400px;">
        <canvas id="ordersChart"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(renderOrdersChart, 500);
        });

        document.addEventListener('livewire:initialized', () => {
            setTimeout(renderOrdersChart, 500);
            
            Livewire.on('chartDataUpdated', () => {
                setTimeout(renderOrdersChart, 200);
            });
        });

        // Listen for Chart.js ready event
        document.addEventListener('chartjsReady', function() {
            console.log('Chart.js ready event received');
            setTimeout(renderOrdersChart, 100);
        });

        function renderOrdersChart() {
            // Check if Chart.js is available
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded - Orders chart cannot render');
                return; // Don't retry anymore, just fail gracefully
            }
            
            const canvas = document.getElementById('ordersChart');
            if (!canvas) {
                console.log('Orders chart canvas not found');
                return;
            }

            // Destroy existing chart
            if (window.ordersChartInstance) {
                window.ordersChartInstance.destroy();
            }

            const chartData = {!! json_encode($chartData) !!};
            
            if (!chartData || !chartData.labels || chartData.labels.length === 0) {
                console.log('No data for orders chart');
                return;
            }

            console.log('Rendering orders chart with data:', chartData);

            const ctx = canvas.getContext('2d');
            
            window.ordersChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Orders',
                        data: chartData.orderCounts,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: true
                    }, {
                        label: 'Revenue ($)',
                        data: chartData.revenues,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.1,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Orders'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });
        }
    </script>
    @else
    <div class="text-center py-8">
        <div class="text-gray-500">No chart data available for the selected period</div>
    </div>
    @endif
</div>
