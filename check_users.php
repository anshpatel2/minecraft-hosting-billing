<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== USERS TABLE COLUMNS ===\n";

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
foreach ($columns as $column) {
    echo "- $column\n";
}

echo "\n=== FIRST USER DATA ===\n";
$user = \App\Models\User::first();
if ($user) {
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    print_r($user->toArray());
}
