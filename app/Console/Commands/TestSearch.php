<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Models\Plan;

class TestSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:search {search?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the search functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $search = $this->argument('search') ?? 'John';
        
        $this->info("Testing search for: {$search}");
        
        // Test the search query from OrderManager
        $query = Order::with(['user', 'plan']);
        
        $query->where(function($q) use ($search) {
            $q->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhereHas('plan', function ($planQuery) use ($search) {
                $planQuery->where('name', 'like', '%' . $search . '%');
            })->orWhere('order_number', 'like', '%' . $search . '%')
              ->orWhere('id', 'like', '%' . $search . '%');
        });
        
        $orders = $query->get();
        
        $this->info("Found {$orders->count()} orders");
        
        foreach ($orders as $order) {
            $this->line("Order #{$order->id} - User: {$order->user->name} - Plan: {$order->plan->name} - Status: {$order->status}");
        }
        
        // Test data counts
        $this->info("\nData Summary:");
        $this->info("Total Orders: " . Order::count());
        $this->info("Total Users: " . User::count());
        $this->info("Total Plans: " . Plan::count());
    }
}
