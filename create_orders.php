<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CREATING SAMPLE ORDERS ===\n";

$users = \App\Models\User::all();
$plans = \App\Models\Plan::all();

if ($users->count() > 0 && $plans->count() > 0) {
    // Create 10 sample orders
    for ($i = 1; $i <= 10; $i++) {
        $user = $users->random();
        $plan = $plans->random();
        $status = ['pending', 'active', 'suspended', 'cancelled'][array_rand(['pending', 'active', 'suspended', 'cancelled'])];
        
        \App\Models\Order::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'order_number' => 'ORD-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
            'quantity' => rand(1, 3),
            'total_price' => $plan->price * rand(1, 3),
            'amount' => $plan->price * rand(1, 3),
            'status' => $status,
            'payment_method' => ['credit_card', 'paypal', 'bank_transfer'][array_rand(['credit_card', 'paypal', 'bank_transfer'])],
            'billing_cycle' => ['monthly', 'quarterly', 'yearly'][array_rand(['monthly', 'quarterly', 'yearly'])],
            'due_date' => now()->addDays(rand(1, 30)),
            'paid_at' => $status === 'active' ? now()->subDays(rand(1, 10)) : null,
            'notes' => 'Sample order created for testing',
            'created_at' => now()->subDays(rand(1, 30)),
        ]);
        
        echo "Created order #$i\n";
    }
    
    echo "\nTotal Orders: " . \App\Models\Order::count() . "\n";
} else {
    echo "No users or plans found!\n";
}
