<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Server;
use App\Models\Plan;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\Activity;
use Illuminate\Support\Str;

class DynamicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some plans if they don't exist
        $plans = [
            [
                'name' => 'Basic', 
                'description' => 'Perfect for small servers', 
                'price' => 9.99, 
                'billing_cycle' => 'monthly',
                'ram_gb' => 1,
                'storage_gb' => 10,
                'max_players' => 10,
                'memory' => 1024, 
                'players' => 10,
                'is_active' => true,
                'features' => ['Basic Support', 'Daily Backups']
            ],
            [
                'name' => 'Standard', 
                'description' => 'Great for medium servers', 
                'price' => 19.99, 
                'billing_cycle' => 'monthly',
                'ram_gb' => 2,
                'storage_gb' => 25,
                'max_players' => 20,
                'memory' => 2048, 
                'players' => 20,
                'is_active' => true,
                'features' => ['Priority Support', 'Daily Backups', 'Custom Plugins']
            ],
            [
                'name' => 'Premium', 
                'description' => 'Ideal for large communities', 
                'price' => 39.99, 
                'billing_cycle' => 'monthly',
                'ram_gb' => 4,
                'storage_gb' => 50,
                'max_players' => 50,
                'memory' => 4096, 
                'players' => 50,
                'is_active' => true,
                'features' => ['24/7 Support', 'Hourly Backups', 'Custom Plugins', 'DDoS Protection']
            ],
            [
                'name' => 'Enterprise', 
                'description' => 'Maximum performance for huge servers', 
                'price' => 79.99, 
                'billing_cycle' => 'monthly',
                'ram_gb' => 8,
                'storage_gb' => 100,
                'max_players' => 100,
                'memory' => 8192, 
                'players' => 100,
                'is_active' => true,
                'features' => ['Dedicated Support', 'Real-time Backups', 'Custom Plugins', 'DDoS Protection', 'Priority Hardware']
            ],
        ];

        foreach ($plans as $planData) {
            Plan::firstOrCreate(
                ['name' => $planData['name']],
                $planData
            );
        }

        // Get all users and add dynamic data
        $users = User::all();
        
        foreach ($users as $user) {
            // Update user billing info
            $user->update([
                'balance' => rand(0, 500) + (rand(0, 99) / 100),
                'monthly_spend' => rand(10, 100) + (rand(0, 99) / 100),
                'total_paid' => rand(100, 1000) + (rand(0, 99) / 100),
                'outstanding_balance' => rand(0, 50) + (rand(0, 99) / 100),
                'monthly_revenue' => $user->hasRole('reseller') ? rand(500, 2000) + (rand(0, 99) / 100) : 0,
                'commission_rate' => $user->hasRole('reseller') ? rand(10, 25) : 0,
            ]);

            // Create servers for customers
            if ($user->hasRole('customer') && rand(0, 1)) {
                $serverCount = rand(1, 3);
                for ($i = 0; $i < $serverCount; $i++) {
                    $plan = Plan::inRandomOrder()->first();
                    $status = ['active', 'stopped', 'suspended'][rand(0, 2)];
                    
                    Server::create([
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'name' => 'Server-' . Str::random(8),
                        'server_id' => 'srv_' . Str::random(12),
                        'status' => $status,
                        'minecraft_version' => ['1.20.1', '1.19.4', '1.18.2'][rand(0, 2)],
                        'server_type' => ['vanilla', 'spigot', 'paper', 'forge'][rand(0, 3)],
                        'ip_address' => '192.168.1.' . rand(100, 200),
                        'port' => 25565 + $i,
                        'current_players' => $status === 'active' ? rand(0, $plan->players ?? 20) : 0,
                        'max_players' => $plan->players ?? 20,
                        'memory_usage' => $status === 'active' ? rand(100, $plan->memory ?? 1024) : 0,
                        'max_memory' => $plan->memory ?? 1024,
                        'uptime' => $status === 'active' ? rand(1, 720) . 'h ' . rand(0, 59) . 'm' : '0h 0m',
                        'monthly_cost' => $plan->price ?? 9.99,
                    ]);
                }
            }

            // Create invoices
            if (rand(0, 1)) {
                $invoiceCount = rand(1, 5);
                for ($i = 0; $i < $invoiceCount; $i++) {
                    $status = ['paid', 'pending', 'overdue'][rand(0, 2)];
                    $amount = rand(10, 200) + (rand(0, 99) / 100);
                    
                    Invoice::create([
                        'user_id' => $user->id,
                        'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                        'amount' => $amount,
                        'status' => $status,
                        'due_date' => now()->addDays(rand(-30, 30)),
                        'paid_date' => $status === 'paid' ? now()->subDays(rand(1, 30)) : null,
                        'description' => 'Monthly server hosting fee',
                        'line_items' => [
                            ['description' => 'Server hosting', 'amount' => $amount]
                        ],
                    ]);
                }
            }

            // Create payment methods
            if (rand(0, 1)) {
                $methodCount = rand(1, 3);
                for ($i = 0; $i < $methodCount; $i++) {
                    PaymentMethod::create([
                        'user_id' => $user->id,
                        'type' => 'card',
                        'brand' => ['visa', 'mastercard', 'amex'][rand(0, 2)],
                        'last_four' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                        'exp_month' => str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT),
                        'exp_year' => date('Y') + rand(1, 5),
                        'is_default' => $i === 0, // First one is default
                    ]);
                }
            }

            // Create activities
            $activityCount = rand(3, 10);
            for ($i = 0; $i < $activityCount; $i++) {
                $activities = [
                    ['type' => 'success', 'icon' => 'check-circle', 'title' => 'Server Started', 'description' => 'Your Minecraft server has been successfully started.'],
                    ['type' => 'info', 'icon' => 'info-circle', 'title' => 'Payment Received', 'description' => 'Payment of $29.99 received for your monthly subscription.'],
                    ['type' => 'warning', 'icon' => 'exclamation-triangle', 'title' => 'High Memory Usage', 'description' => 'Your server is using 85% of available memory.'],
                    ['type' => 'success', 'icon' => 'user-plus', 'title' => 'New Player Joined', 'description' => 'Player "Steve123" joined your server.'],
                    ['type' => 'info', 'icon' => 'download', 'title' => 'Backup Created', 'description' => 'Automatic backup of your server has been created.'],
                ];
                
                $activity = $activities[rand(0, 4)];
                
                Activity::create([
                    'user_id' => $user->id,
                    'type' => $activity['type'],
                    'icon' => $activity['icon'],
                    'title' => $activity['title'],
                    'description' => $activity['description'],
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                ]);
            }
        }
    }
}
