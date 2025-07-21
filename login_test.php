<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the user with servers
$user = \App\Models\User::find(4); // ansh user with servers

if ($user) {
    // Manually authenticate the user
    \Illuminate\Support\Facades\Auth::login($user);
    echo "Logged in as: " . $user->name . "\n";
    echo "Servers: " . $user->servers()->count() . "\n";
    echo "User ID: " . $user->id . "\n";
} else {
    echo "User not found\n";
}
