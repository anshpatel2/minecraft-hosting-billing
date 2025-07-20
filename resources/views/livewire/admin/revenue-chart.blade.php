<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Monthly Revenue Chart -->
    <div class="admin-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Monthly Revenue</h3>
            <button wire:click="refreshData" class="text-sm text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>

        <div class="chart-container" style="position: relative; height: 300px;">
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>

    <!-- Plan Subscriptions Chart -->
    <div class="admin-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Plan Subscriptions</h3>
        </div>

        @if($planSubscriptions && $planSubscriptions->count() > 0)
        <div class="chart-container" style="position: relative; height: 300px;">
            <canvas id="planSubscriptionsChart"></canvas>
        </div>

        <!-- Legend -->
        <div class="mt-4 grid grid-cols-1 gap-2">
            @foreach($planSubscriptions as $index => $plan)
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded mr-2" style="background-color: {{ $this->getChartColor($index) }}"></div>
                    <span class="text-sm font-medium">{{ $plan['name'] }}</span>
                </div>
                <div class="text-sm text-gray-600">
                    {{ $plan['count'] }} orders • ${{ number_format($plan['revenue'], 2) }}
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <div class="text-gray-500">No plan subscription data available</div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(renderCharts, 500);
        });

        document.addEventListener('livewire:initialized', () => {
            setTimeout(renderCharts, 500);
            
            Livewire.on('chartDataUpdated', () => {
                setTimeout(renderCharts, 200);
            });
        });

        // Listen for Chart.js ready event
        document.addEventListener('chartjsReady', function() {
            console.log('Chart.js ready event received for revenue charts');
            setTimeout(renderCharts, 100);
        });

        function renderCharts() {
            // Check if Chart.js is available
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded - Revenue charts cannot render');
                return; // Don't retry anymore, just fail gracefully
            }
            
            renderMonthlyRevenueChart();
            renderPlanSubscriptionsChart();
        }

        function renderMonthlyRevenueChart() {
            const canvas = document.getElementById('monthlyRevenueChart');
            if (!canvas) {
                console.log('Monthly revenue chart canvas not found');
                return;
            }

            // Destroy existing chart
            if (window.monthlyRevenueChartInstance) {
                window.monthlyRevenueChartInstance.destroy();
            }

            const monthlyData = {!! json_encode($monthlyRevenue) !!};
            
            if (!monthlyData || monthlyData.length === 0) {
                console.log('No monthly revenue data available');
                return;
            }

            console.log('Monthly revenue data:', monthlyData);

            const ctx = canvas.getContext('2d');
            
            window.monthlyRevenueChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Revenue ($)',
                        data: monthlyData.map(item => item.revenue),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        }
                    }
                }
            });
        }

        function renderPlanSubscriptionsChart() {
            const canvas = document.getElementById('planSubscriptionsChart');
            if (!canvas) {
                console.log('Plan subscriptions chart canvas not found');
                return;
            }

            // Destroy existing chart
            if (window.planSubscriptionsChartInstance) {
                window.planSubscriptionsChartInstance.destroy();
            }

            const planData = {!! json_encode($planSubscriptions) !!};
            
            if (!planData || planData.length === 0) {
                console.log('No plan subscription data available');
                return;
            }

            console.log('Plan subscription data:', planData);

            const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];
            
            const ctx = canvas.getContext('2d');
            
            window.planSubscriptionsChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: planData.map(plan => plan.name),
                    datasets: [{
                        data: planData.map(plan => plan.count),
                        backgroundColor: colors.slice(0, planData.length),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>
</div>
