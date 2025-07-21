<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Move servers to Test User
$testUser = \App\Models\User::find(1);
$servers = \App\Models\Server::where('user_id', 4)->get();

foreach ($servers as $server) {
    $server->user_id = 1;
    $server->save();
    echo "Moved server: " . $server->name . " to " . $testUser->name . "\n";
}

// Move some invoices too
$invoices = \App\Models\Invoice::where('user_id', 4)->take(3)->get();
foreach ($invoices as $invoice) {
    $invoice->user_id = 1;
    $invoice->save();
    echo "Moved invoice: " . $invoice->invoice_number . " to " . $testUser->name . "\n";
}

echo "\nTest User now has:\n";
echo "- Servers: " . $testUser->servers()->count() . "\n";
echo "- Invoices: " . $testUser->invoices()->count() . "\n";
