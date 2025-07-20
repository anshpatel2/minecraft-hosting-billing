<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Carbon\Carbon;

class OrdersChart extends Component
{
    public $chartData;
    public $period = '30'; // days

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $days = (int) $this->period;
        $startDate = Carbon::now()->subDays($days);
        
        $orders = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $orderCounts = [];
        $revenues = [];

        // Fill in missing dates
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('M j');
            
            $dayData = $orders->firstWhere('date', $date);
            $orderCounts[] = $dayData ? $dayData->count : 0;
            $revenues[] = $dayData ? floatval($dayData->revenue) : 0;
        }

        $this->chartData = [
            'labels' => $labels,
            'orderCounts' => $orderCounts,
            'revenues' => $revenues,
        ];
    }

    public function updatedPeriod()
    {
        $this->loadChartData();
        $this->dispatch('chartDataUpdated');
    }

    public function render()
    {
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Create array of last 7 days
        $dates = [];
        $counts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M j');
            
            $order = $orders->firstWhere('date', $date);
            $counts[] = $order ? $order->count : 0;
        }

        return view('livewire.admin.orders-chart', [
            'dates' => json_encode($dates),
            'counts' => json_encode($counts)
        ]);
    }
}
