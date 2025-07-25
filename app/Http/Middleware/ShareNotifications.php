<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareNotifications
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Share notification data with all views
            View::share('unreadNotificationsCount', $user->unreadNotifications()->count());
            View::share('hasUnreadNotifications', $user->hasUnreadNotifications());
            View::share('recentNotifications', $user->notifications()->latest()->limit(5)->get());
        }

        return $next($request);
    }
}
