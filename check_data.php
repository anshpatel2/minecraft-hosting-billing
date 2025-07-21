<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DATABASE CHECK ===\n";

// Check users
$users = \App\Models\User::all();
echo "Total Users: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "User: {$user->name} (ID: {$user->id}) - Email: {$user->email}\n";
    echo "  - Servers: " . $user->servers()->count() . "\n";
    echo "  - Invoices: " . $user->invoices()->count() . "\n";
}

echo "\n=== SERVERS ===\n";
$servers = \App\Models\Server::all();
foreach ($servers as $server) {
    echo "Server: {$server->name} - Owner: {$server->user->name} - Status: {$server->status}\n";
}

echo "\n=== PLANS ===\n";
$plans = \App\Models\Plan::all();
foreach ($plans as $plan) {
    echo "Plan: " . $plan->name . " - Price: $" . $plan->price . "\n";
}
