<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ALL USERS AND THEIR ROLES ===\n";

$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "User: " . $user->name . " - Role: " . $user->role . " - ID: " . $user->id . "\n";
}

// Update John Reseller's role
$johnReseller = \App\Models\User::where('name', 'John Reseller')->first();
if ($johnReseller) {
    $johnReseller->update(['role' => 'reseller']);
    echo "\nUpdated John Reseller's role to 'reseller'\n";
}

echo "\n=== AFTER UPDATE ===\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "User: " . $user->name . " - Role: " . $user->role . " - ID: " . $user->id . "\n";
}
