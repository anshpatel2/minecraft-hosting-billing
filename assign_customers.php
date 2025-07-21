<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ASSIGNING CUSTOMERS TO RESELLER ===\n";

$reseller = \App\Models\User::where('role', 'reseller')->first();

if (!$reseller) {
    echo "No reseller found!\n";
    exit;
}

echo "Found reseller: " . $reseller->name . "\n";

// Get users without reseller assignment
$customers = \App\Models\User::whereIn('role', ['customer', 'admin'])
    ->whereNull('reseller_id')
    ->limit(3)
    ->get();

foreach ($customers as $customer) {
    $customer->update([
        'reseller_id' => $reseller->id,
        'monthly_spend' => rand(25, 150),
        'servers_count' => $customer->servers()->count(),
    ]);
    echo "Assigned customer: " . $customer->name . " (servers: " . $customer->servers_count . ")\n";
}

echo "\n=== FINAL RESELLER STATS ===\n";
echo "Reseller: " . $reseller->name . "\n";
echo "Total Customers: " . $reseller->customers()->count() . "\n";
echo "Commission Rate: " . $reseller->commission_rate . "%\n";
echo "Monthly Revenue: $" . $reseller->monthly_revenue . "\n";

echo "\n=== CUSTOMER DETAILS ===\n";
foreach ($reseller->customers as $customer) {
    echo "- " . $customer->name . ": " . $customer->servers()->count() . " servers, $" . $customer->monthly_spend . "/month\n";
}
