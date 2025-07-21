<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== UPDATING DYNAMIC RESELLER DATA ===\n";

// Update commission rates and monthly revenue for resellers
$reseller = \App\Models\User::where('role', 'reseller')->first();
if (!$reseller) {
    // Create a reseller if none exists
    $reseller = \App\Models\User::create([
        'name' => 'John Reseller',
        'email' => 'reseller@example.com',
        'password' => bcrypt('password'),
        'role' => 'reseller',
        'commission_rate' => 15.00,
        'monthly_revenue' => 850.50,
        'email_verified_at' => now(),
    ]);
    
    // Assign reseller role
    $reseller->assignRole('reseller');
    echo "Created reseller: " . $reseller->name . "\n";
}

// Update existing reseller data
$reseller->update([
    'commission_rate' => 15.00,
    'monthly_revenue' => 850.50,
]);

// Assign some customers to the reseller
$customers = \App\Models\User::where('role', 'customer')->take(2)->get();
foreach ($customers as $customer) {
    $customer->update([
        'reseller_id' => $reseller->id,
        'monthly_spend' => rand(20, 100),
        'servers_count' => $customer->servers()->count(),
    ]);
    echo "Assigned customer: " . $customer->name . " to reseller\n";
}

// Update all users' server counts
$users = \App\Models\User::all();
foreach ($users as $user) {
    $user->update([
        'servers_count' => $user->servers()->count(),
    ]);
}

echo "\n=== RESELLER DATA UPDATED ===\n";
echo "Reseller: " . $reseller->name . "\n";
echo "Customers: " . $reseller->customers()->count() . "\n";
echo "Commission Rate: " . $reseller->commission_rate . "%\n";
echo "Monthly Revenue: $" . $reseller->monthly_revenue . "\n";
