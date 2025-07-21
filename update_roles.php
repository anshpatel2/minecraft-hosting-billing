<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== UPDATING ROLES DIRECTLY ===\n";

// Update role directly
\Illuminate\Support\Facades\DB::table('users')->where('name', 'John Reseller')->update(['role' => 'reseller']);
\Illuminate\Support\Facades\DB::table('users')->where('name', 'Unverified User')->update(['role' => 'customer']);
\Illuminate\Support\Facades\DB::table('users')->where('name', 'ansh')->update(['role' => 'customer']);

echo "Updated roles directly\n";

// Now assign customers to reseller
$reseller = \App\Models\User::where('role', 'reseller')->first();
$customers = \App\Models\User::where('role', 'customer')->get();

foreach ($customers as $customer) {
    $customer->update([
        'reseller_id' => $reseller->id,
        'monthly_spend' => rand(25, 150),
        'servers_count' => $customer->servers()->count(),
    ]);
    echo "Assigned customer: " . $customer->name . " to reseller\n";
}

echo "\n=== FINAL STATS ===\n";
echo "Reseller: " . $reseller->name . "\n";
echo "Customers: " . $reseller->customers()->count() . "\n";
