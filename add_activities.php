<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ADDING DYNAMIC ACTIVITY DATA ===\n";

$users = \App\Models\User::all();

$activities = [
    'server_created' => [
        'icon' => 'server',
        'titles' => [
            'New Server Created',
            'Server Deployment Started',
            'Server Configuration Complete',
        ],
        'descriptions' => [
            'Successfully created a new Minecraft server',
            'Server deployment initiated with latest configurations',
            'Server setup completed and ready for players',
        ]
    ],
    'payment_processed' => [
        'icon' => 'credit-card',
        'titles' => [
            'Payment Processed',
            'Invoice Paid',
            'Subscription Renewed',
        ],
        'descriptions' => [
            'Payment of $XX.XX processed successfully',
            'Monthly invoice payment completed',
            'Subscription automatically renewed',
        ]
    ],
    'server_maintenance' => [
        'icon' => 'tools',
        'titles' => [
            'Server Maintenance',
            'Backup Completed',
            'Plugin Updated',
        ],
        'descriptions' => [
            'Scheduled maintenance completed successfully',
            'Daily backup process finished',
            'Server plugins updated to latest versions',
        ]
    ],
    'user_login' => [
        'icon' => 'sign-in-alt',
        'titles' => [
            'User Login',
            'Access Granted',
            'Session Started',
        ],
        'descriptions' => [
            'User successfully logged into control panel',
            'Administrative access granted',
            'New management session initiated',
        ]
    ]
];

// Add activities for each user
foreach ($users as $user) {
    // Add 3-5 random activities per user
    $activityCount = rand(3, 5);
    
    for ($i = 0; $i < $activityCount; $i++) {
        $activityType = array_rand($activities);
        $activityData = $activities[$activityType];
        
        $title = $activityData['titles'][array_rand($activityData['titles'])];
        $description = $activityData['descriptions'][array_rand($activityData['descriptions'])];
        
        // Replace placeholders
        if (strpos($description, '$XX.XX') !== false) {
            $amount = number_format(rand(999, 9999) / 100, 2);
            $description = str_replace('$XX.XX', '$' . $amount, $description);
        }
        
        \App\Models\Activity::create([
            'user_id' => $user->id,
            'type' => $activityType,
            'icon' => $activityData['icon'],
            'title' => $title,
            'description' => $description,
            'created_at' => now()->subHours(rand(1, 72)),
        ]);
    }
    
    echo "Added activities for: " . $user->name . "\n";
}

echo "\n=== ACTIVITY SUMMARY ===\n";
echo "Total Activities: " . \App\Models\Activity::count() . "\n";
echo "Recent Activities: " . \App\Models\Activity::where('created_at', '>=', now()->subDays(7))->count() . "\n";
