<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Plan;
use Livewire\Component;
use Carbon\Carbon;

class RevenueChart extends Component
{
    public $planSubscriptions;
    public $monthlyRevenue;

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        // Plan subscriptions (pie chart data)
        $this->planSubscriptions = Plan::withCount(['orders' => function($query) {
                $query->where('status', 'completed');
            }])
            ->where('is_active', true)
            ->get()
            ->map(function($plan) {
                return [
                    'name' => $plan->name,
                    'count' => $plan->orders_count,
                    'revenue' => $plan->orders()->where('status', 'completed')->sum('amount')
                ];
            });

        // Monthly revenue for the last 12 months
        $this->monthlyRevenue = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Order::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            
            $this->monthlyRevenue->push([
                'month' => $month->format('M Y'),
                'revenue' => floatval($revenue)
            ]);
        }
    }

    public function refreshData()
    {
        $this->loadChartData();
        $this->dispatch('chartDataUpdated');
    }

    public function getChartColor($index)
    {
        $colors = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
            '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'
        ];
        return $colors[$index % count($colors)];
    }

    public function render()
    {
        return view('livewire.admin.revenue-chart', [
            'planSubscriptions' => $this->planSubscriptions,
            'monthlyRevenue' => $this->monthlyRevenue
        ]);
    }
}
