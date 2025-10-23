<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $notifications = Notification::where(function($query) {
                    $query->where('user_id', Auth::id())
                          ->orWhereNull('user_id');
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $unreadCount = Notification::where(function($query) {
                    $query->where('user_id', Auth::id())
                          ->orWhereNull('user_id');
                })
                ->unread()
                ->count();

            $view->with('notifications', $notifications)
                 ->with('unreadNotificationsCount', $unreadCount);
        }
    }
}
