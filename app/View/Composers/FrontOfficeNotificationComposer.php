<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FrontOfficeNotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with([
                'notifications' => collect([]),
                'unreadNotificationsCount' => 0,
            ]);
            return;
        }

        // Get last 5 notifications for the current user
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Count unread notifications
        $unreadNotificationsCount = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        $view->with([
            'notifications' => $notifications,
            'unreadNotificationsCount' => $unreadNotificationsCount,
        ]);
    }
}
