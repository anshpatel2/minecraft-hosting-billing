<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample hosting plans
        $plans = [
            [
                'name' => 'Starter',
                'description' => 'Perfect for small servers with friends',
                'price' => 9.99,
                'billing_cycle' => 'monthly',
                'ram_gb' => 2,
                'storage_gb' => 10,
                'max_players' => 10,
                'is_active' => true,
                'features' => [
                    '24/7 Support',
                    'DDoS Protection',
                    'Automatic Backups',
                    'Plugin Support'
                ]
            ],
            [
                'name' => 'Professional',
                'description' => 'Great for growing communities',
                'price' => 19.99,
                'billing_cycle' => 'monthly',
                'ram_gb' => 4,
                'storage_gb' => 25,
                'max_players' => 50,
                'is_active' => true,
                'features' => [
                    '24/7 Support',
                    'DDoS Protection',
                    'Automatic Backups',
                    'Plugin Support',
                    'Mod Support',
                    'Priority Support'
                ]
            ],
            [
                'name' => 'Enterprise',
                'description' => 'For large servers and networks',
                'price' => 39.99,
                'billing_cycle' => 'monthly',
                'ram_gb' => 8,
                'storage_gb' => 50,
                'max_players' => 100,
                'is_active' => true,
                'features' => [
                    '24/7 Support',
                    'DDoS Protection',
                    'Automatic Backups',
                    'Plugin Support',
                    'Mod Support',
                    'Priority Support',
                    'Dedicated IP',
                    'Custom Java Args'
                ]
            ]
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }

        // Get some users (assuming they exist)
        $users = User::where('role', '!=', 'admin')->limit(5)->get();
        
        if ($users->count() === 0) {
            // Create a test user if none exist
            $testUser = User::create([
                'name' => 'Test Customer',
                'email' => 'customer@test.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'user'
            ]);
            $users = collect([$testUser]);
        }

        $createdPlans = Plan::all();

        // Create sample orders with varying dates for chart data
        $orderDates = [
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(25),
            Carbon::now()->subDays(20),
            Carbon::now()->subDays(15),
            Carbon::now()->subDays(10),
            Carbon::now()->subDays(5),
            Carbon::now()->subDays(2),
            Carbon::now()->subDay(),
            Carbon::now()
        ];

        foreach ($orderDates as $index => $date) {
            $user = $users->random();
            $plan = $createdPlans->random();
            
            $order = Order::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'amount' => $plan->price,
                'status' => collect(['pending', 'completed', 'cancelled'])->random(),
                'payment_method' => collect(['stripe', 'paypal', 'crypto'])->random(),
                'paid_at' => $date,
                'created_at' => $date,
                'updated_at' => $date
            ]);

            // Create a server for completed orders
            if ($order->status === 'completed') {
                Server::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'order_id' => $order->id,
                    'name' => 'Server-' . $order->order_number,
                    'server_id' => 'srv-' . strtolower(uniqid()),
                    'status' => collect(['active', 'suspended', 'creating'])->random(),
                    'minecraft_version' => collect(['1.20.1', '1.19.4', '1.18.2'])->random(),
                    'server_type' => collect(['vanilla', 'spigot', 'paper'])->random(),
                    'ip_address' => '192.168.1.' . rand(100, 200),
                    'port' => 25565,
                    'last_online' => $date,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }

        $this->command->info('Sample data created successfully!');
        $this->command->info('Created ' . Plan::count() . ' plans');
        $this->command->info('Created ' . Order::count() . ' orders');
        $this->command->info('Created ' . Server::count() . ' servers');
    }
}
