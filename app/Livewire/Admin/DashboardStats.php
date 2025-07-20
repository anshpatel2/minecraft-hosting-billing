<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Server;
use App\Models\Plan;
use Livewire\Component;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public $totalUsers;
    public $totalOrders;
    public $totalRevenue;
    public $activeServers;
    public $monthlyRevenue;
    public $pendingOrders;
    public $totalPlans;
    public $recentOrdersCount;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalUsers = User::count();
        $this->totalOrders = Order::count();
        $this->totalRevenue = Order::where('status', 'completed')->sum('amount');
        $this->activeServers = Server::where('status', 'active')->count();
        $this->monthlyRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $this->pendingOrders = Order::where('status', 'pending')->count();
        $this->totalPlans = Plan::where('is_active', true)->count();
        $this->recentOrdersCount = Order::where('created_at', '>=', Carbon::now()->subDays(7))->count();
    }

    public function refreshStats()
    {
        $this->loadStats();
    }

    public function render()
    {
        $stats = [
            'total_users' => User::count(),
            'active_servers' => Server::where('status', 'active')->count(),
            'monthly_revenue' => Order::where('status', 'completed')
                ->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])->sum('amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('livewire.admin.dashboard-stats', compact('stats'));
    }
}
