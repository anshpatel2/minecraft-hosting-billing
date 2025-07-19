<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->getAdminNotifications();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        // In a real application, you would update the notification status in the database
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // In a real application, you would update all notifications for the user
        return response()->json(['success' => true]);
    }

    private function getAdminNotifications()
    {
        // Sample notifications - in a real app, these would come from the database
        return [
            [
                'id' => 1,
                'title' => 'New User Registration',
                'message' => 'John Doe has registered and is pending email verification.',
                'type' => 'info',
                'time' => '2 minutes ago',
                'read' => false,
                'icon' => 'user-add',
                'url' => route('admin.users')
            ],
            [
                'id' => 2,
                'title' => 'System Update Available',
                'message' => 'Laravel 12.1 security update is now available.',
                'type' => 'warning',
                'time' => '1 hour ago',
                'read' => false,
                'icon' => 'exclamation-triangle',
                'url' => '#'
            ],
            [
                'id' => 3,
                'title' => 'Server Maintenance',
                'message' => 'Scheduled maintenance will begin at 2:00 AM UTC.',
                'type' => 'info',
                'time' => '3 hours ago',
                'read' => true,
                'icon' => 'server',
                'url' => '#'
            ],
            [
                'id' => 4,
                'title' => 'Payment Received',
                'message' => 'Payment of $29.99 received from user@example.com.',
                'type' => 'success',
                'time' => '1 day ago',
                'read' => true,
                'icon' => 'cash',
                'url' => '#'
            ],
            [
                'id' => 5,
                'title' => 'Security Alert',
                'message' => 'Multiple failed login attempts detected.',
                'type' => 'danger',
                'time' => '2 days ago',
                'read' => false,
                'icon' => 'shield-exclamation',
                'url' => '#'
            ]
        ];
    }
}
