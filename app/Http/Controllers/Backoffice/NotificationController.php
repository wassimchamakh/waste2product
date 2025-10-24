<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get recent notifications for the navbar (last 5 unread)
     */
    public function getRecent()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id') // Global notifications
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = Notification::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id');

        // Filter by read status
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Notification::where('user_id', Auth::id())
                ->orWhereNull('user_id')
                ->count(),
            'unread' => Notification::where(function($q) {
                    $q->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
                })
                ->unread()
                ->count(),
            'read' => Notification::where(function($q) {
                    $q->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
                })
                ->read()
                ->count(),
        ];

        return view('BackOffice.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        // If it's an AJAX request, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        // Otherwise redirect back
        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification supprimée');
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        Notification::where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhereNull('user_id');
            })
            ->read()
            ->delete();

        return back()->with('success', 'Toutes les notifications lues ont été supprimées');
    }
}
