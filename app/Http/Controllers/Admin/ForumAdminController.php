<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ForumAdminController
 * 
 * Admin panel for moderating forum posts and comments.
 */
class ForumAdminController extends Controller
{
    /**
     * Admin dashboard - overview of forum activity.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check admin authorization
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $stats = [
            'total_posts' => ForumPost::count(),
            'published_posts' => ForumPost::where('status', 'published')->count(),
            'spam_posts' => ForumPost::where('is_spam', true)->count(),
            'total_comments' => ForumComment::count(),
            'spam_comments' => ForumComment::where('is_spam', true)->count(),
            'total_users' => User::whereHas('forumPosts')->orWhereHas('forumComments')->count(),
            'pinned_posts' => ForumPost::where('is_pinned', true)->count(),
            'locked_posts' => ForumPost::where('is_locked', true)->count(),
        ];

        // Recent activity
        $recentPosts = ForumPost::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentComments = ForumComment::with(['user', 'forumPost'])
            ->latest()
            ->take(10)
            ->get();

        // Spam queue
        $spamPosts = ForumPost::where('is_spam', true)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $spamComments = ForumComment::where('is_spam', true)
            ->with(['user', 'forumPost'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.forum.index', compact(
            'stats',
            'recentPosts',
            'recentComments',
            'spamPosts',
            'spamComments'
        ));
    }

    /**
     * List all posts with moderation options.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function posts(Request $request)
    {
        $query = ForumPost::with('user')->withCount('allComments');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('spam')) {
            $query->where('is_spam', $request->spam === 'yes');
        }

        if ($request->filled('pinned')) {
            $query->where('is_pinned', $request->pinned === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('body', 'LIKE', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(20);

        return view('admin.forum.posts', compact('posts'));
    }

    /**
     * List all comments with moderation options.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function comments(Request $request)
    {
        $query = ForumComment::with(['user', 'forumPost']);

        // Filters
        if ($request->filled('spam')) {
            $query->where('is_spam', $request->spam === 'yes');
        }

        if ($request->filled('best_answer')) {
            $query->where('is_best_answer', $request->best_answer === 'yes');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('body', 'LIKE', "%{$search}%");
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.forum.comments', compact('comments'));
    }

    /**
     * Toggle post pin status.
     * 
     * @param ForumPost $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function togglePin(ForumPost $post)
    {
        $post->update(['is_pinned' => !$post->is_pinned]);

        $message = $post->is_pinned ? '📌 Post épinglé.' : 'Post désépinglé.';
        return back()->with('success', $message);
    }

    /**
     * Toggle post lock status.
     * 
     * @param ForumPost $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleLock(ForumPost $post)
    {
        $post->update(['is_locked' => !$post->is_locked]);

        $message = $post->is_locked ? '🔒 Post verrouillé.' : '🔓 Post déverrouillé.';
        return back()->with('success', $message);
    }

    /**
     * Toggle spam status (post or comment).
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleSpam(Request $request)
    {
        $type = $request->type; // 'post' or 'comment'
        $id = $request->id;

        if ($type === 'post') {
            $item = ForumPost::findOrFail($id);
        } else {
            $item = ForumComment::findOrFail($id);
        }

        $item->update(['is_spam' => !$item->is_spam]);

        $message = $item->is_spam ? '⚠️ Marqué comme spam.' : '✅ Spam retiré.';
        return back()->with('success', $message);
    }

    /**
     * Delete a post (admin override).
     * 
     * @param ForumPost $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPost(ForumPost $post)
    {
        $post->delete();

        return back()->with('success', '✅ Post supprimé.');
    }

    /**
     * Delete a comment (admin override).
     * 
     * @param ForumComment $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyComment(ForumComment $comment)
    {
        $comment->delete();

        return back()->with('success', '✅ Commentaire supprimé.');
    }

    /**
     * Change post status (publish, draft, archive).
     * 
     * @param Request $request
     * @param ForumPost $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, ForumPost $post)
    {
        $request->validate([
            'status' => 'required|in:published,draft,archived',
        ]);

        $post->update(['status' => $request->status]);

        return back()->with('success', '✅ Statut modifié.');
    }

    /**
     * User reputation management.
     * 
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::whereHas('forumPosts')
            ->orWhereHas('forumComments')
            ->with(['forumPosts', 'forumComments'])
            ->orderBy('reputation', 'desc')
            ->paginate(30);

        return view('admin.forum.users', compact('users'));
    }
}
