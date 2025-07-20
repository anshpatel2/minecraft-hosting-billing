<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()->latest();
        
        // Filter by read status
        if ($request->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(data, '$.title') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(data, '$.message') LIKE ?", ["%{$search}%"]);
            });
        }
        
        $notifications = $query->paginate(15);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function overview()
    {
        $totalNotifications = DatabaseNotification::count();
        $totalUnread = DatabaseNotification::whereNull('read_at')->count();
        $totalUsers = User::count();
        $recentNotifications = DatabaseNotification::with('notifiable')
            ->latest()
            ->limit(10)
            ->get();
        
        // Notification statistics by type
        $notificationStats = DatabaseNotification::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();
        
        // Daily notification count for the last 7 days
        $dailyStats = DatabaseNotification::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.notifications.overview', compact(
            'totalNotifications', 
            'totalUnread', 
            'totalUsers', 
            'recentNotifications',
            'notificationStats',
            'dailyStats'
        ));
    }

    public function create()
    {
        $users = User::with('roles')->get();
        $userRoles = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as count'))
            ->groupBy('roles.name')
            ->pluck('count', 'name');
        
        return view('admin.notifications.create', compact('users', 'userRoles'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:all,role,specific',
            'user_id' => 'required_if:recipient_type,specific',
            'role_name' => 'required_if:recipient_type,role',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'url' => 'nullable|url',
        ]);

        $recipients = collect();

        switch ($request->recipient_type) {
            case 'all':
                $recipients = User::all();
                break;
            
            case 'role':
                $recipients = User::role($request->role_name)->get();
                break;
            
            case 'specific':
                if (is_array($request->user_id)) {
                    $recipients = User::whereIn('id', $request->user_id)->get();
                } else {
                    $recipients = User::where('id', $request->user_id)->get();
                }
                break;
        }

        foreach ($recipients as $user) {
            $user->notify(new AdminMessageNotification(
                $request->title, 
                $request->message, 
                $request->url
            ));
        }

        return redirect()->back()->with('success', 
            "Notification sent successfully to {$recipients->count()} user(s)!"
        );
    }

    public function markAsRead(Request $request, $notification)
    {
        $notificationModel = Auth::user()->notifications()->findOrFail($notification);
        $notificationModel->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:mark_read,delete',
            'notification_ids' => 'required|array',
        ]);

        $notifications = Auth::user()->notifications()
            ->whereIn('id', $request->notification_ids);

        switch ($request->action) {
            case 'mark_read':
                $notifications->update(['read_at' => now()]);
                $message = 'Selected notifications marked as read.';
                break;
            
            case 'delete':
                $notifications->delete();
                $message = 'Selected notifications deleted.';
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function globalNotifications()
    {
        $query = DatabaseNotification::with('notifiable')->latest();
        
        $notifications = $query->paginate(20);
        $totalCount = DatabaseNotification::count();
        $unreadCount = DatabaseNotification::whereNull('read_at')->count();
        
        return view('admin.notifications.global', compact('notifications', 'totalCount', 'unreadCount'));
    }

    /**
     * API endpoint for fetching notifications for the dropdown
     */
    public function api()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            });

        $unreadCount = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Global notifications view - alias for consistency
     */
    public function global()
    {
        return $this->globalNotifications();
    }
}
