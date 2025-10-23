<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the user
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id());

        // Filter by read status
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Notification::where('user_id', Auth::id())->count(),
            'unread' => Notification::where('user_id', Auth::id())->unread()->count(),
            'read' => Notification::where('user_id', Auth::id())->read()->count(),
            
            // Stats by type
            'project' => Notification::where('user_id', Auth::id())->where('type', 'project')->count(),
            'event' => Notification::where('user_id', Auth::id())->where('type', 'event')->count(),
            'tutorial' => Notification::where('user_id', Auth::id())->where('type', 'tutorial')->count(),
            'dechet' => Notification::where('user_id', Auth::id())->where('type', 'dechet')->count(),
            'comment' => Notification::where('user_id', Auth::id())->where('type', 'comment')->count(),
            'message' => Notification::where('user_id', Auth::id())->where('type', 'message')->count(),
        ];

        return view('FrontOffice.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
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
        $notification = Notification::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $notification->delete();

        return back()->with('success', 'Notification supprimée');
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->read()
            ->delete();

        return back()->with('success', 'Toutes les notifications lues ont été supprimées');
    }
}
