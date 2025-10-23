<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumVote;
use App\Http\Requests\StoreForumPostRequest;
use App\Http\Requests\UpdateForumPostRequest;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * ForumPostController
 * 
 * Handles all forum post operations including CRUD, voting, and filtering.
 */
class ForumPostController extends Controller
{
    /**
     * Display a listing of forum posts with filters and sorting.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Base query with relationships
        $query = ForumPost::with(['user', 'allComments'])
            ->published()
            ->pinned();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('body', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'popular':
                $query->popular();
                break;
            case 'most_commented':
                $query->mostCommented();
                break;
            case 'recent':
            default:
                $query->recent();
                break;
        }

        $posts = $query->paginate(15);

        // Statistics
        $stats = [
            'total_posts' => ForumPost::published()->count(),
            'total_comments' => DB::table('forum_comments')->whereNull('deleted_at')->count(),
            'active_users' => DB::table('forum_posts')
                ->select('user_id')
                ->distinct()
                ->count(),
        ];

        // Pinned posts (show separately at top)
        $pinnedPosts = ForumPost::with('user')
            ->published()
            ->where('is_pinned', true)
            ->take(3)
            ->get();

        return view('forum.posts.index', compact('posts', 'stats', 'pinnedPosts', 'sort'));
    }

    /**
     * Show the form for creating a new post.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', ForumPost::class);
        
        return view('forum.posts.create');
    }

    /**
     * Store a newly created post in storage.
     * 
     * @param StoreForumPostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreForumPostRequest $request)
    {
        $this->authorize('create', ForumPost::class);

        $post = ForumPost::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::id(),
            'status' => $request->get('status', 'published'),
        ]);

        // Update user's post count
        Auth::user()->increment('posts_count');

        // Award reputation for creating a post
        Auth::user()->increment('reputation', 1);
        Auth::user()->updateBadge();

        // Check if spam was detected
        if ($post->is_spam) {
            return redirect()->route('forum.posts.show', $post)
                ->with('warning', '⚠️ Votre post a été publié mais marqué pour modération en raison de contenu suspect.');
        }

        return redirect()->route('forum.posts.show', $post)
            ->with('success', '✅ Post créé avec succès !');
    }

    /**
     * Display the specified post with comments.
     * 
     * @param ForumPost $post
     * @return \Illuminate\View\View
     */
    public function show(ForumPost $post)
    {
        $this->authorize('view', $post);

        // Increment views
        $post->incrementViews();

        // Load relationships
        $post->load([
            'user',
            'comments.user',
            'comments.replies.user'
        ]);

        // Get user's vote if authenticated
        $userVote = null;
        if (Auth::check()) {
            $userVote = $post->getUserVote(Auth::id());
        }

        // Related posts (same author or similar content)
        $relatedPosts = ForumPost::published()
            ->where('id', '!=', $post->id)
            ->where('user_id', $post->user_id)
            ->latest()
            ->take(3)
            ->get();

        return view('forum.posts.show', compact('post', 'userVote', 'relatedPosts'));
    }

    /**
     * Show the form for editing the specified post.
     * 
     * @param ForumPost $post
     * @return \Illuminate\View\View
     */
    public function edit(ForumPost $post)
    {
        $this->authorize('update', $post);

        return view('forum.posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     * 
     * @param UpdateForumPostRequest $request
     * @param ForumPost $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateForumPostRequest $request, ForumPost $post)
    {
        $this->authorize('update', $post);

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'status' => $request->get('status', $post->status),
        ]);

        return redirect()->route('forum.posts.show', $post)
            ->with('success', '✅ Post mis à jour avec succès !');
    }

    /**
     * Remove the specified post from storage (soft delete).
     * 
     * @param ForumPost $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ForumPost $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        // Decrement user's post count
        $post->user->decrement('posts_count');

        return redirect()->route('forum.posts.index')
            ->with('success', '✅ Post supprimé avec succès.');
    }

    /**
     * Handle voting on a post (AJAX endpoint).
     * 
     * @param Request $request
     * @param ForumPost $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request, ForumPost $post)
    {
        $this->authorize('vote', $post);

        $request->validate([
            'vote' => 'required|in:1,-1',
        ]);

        $voteValue = (int) $request->vote;

        // Check if user already voted
        $existingVote = ForumVote::where('user_id', Auth::id())
            ->where('votable_type', ForumPost::class)
            ->where('votable_id', $post->id)
            ->first();

        if ($existingVote) {
            // If same vote, remove it (toggle)
            if ($existingVote->vote == $voteValue) {
                $existingVote->delete();
                $userVote = 0;
            } else {
                // Change vote
                $existingVote->update(['vote' => $voteValue]);
                $userVote = $voteValue;
            }
        } else {
            // Create new vote
            ForumVote::create([
                'user_id' => Auth::id(),
                'votable_type' => ForumPost::class,
                'votable_id' => $post->id,
                'vote' => $voteValue,
            ]);
            $userVote = $voteValue;
        }

        // Refresh post to get updated vote counts
        $post->refresh();

        return response()->json([
            'success' => true,
            'upvotes' => $post->upvotes,
            'downvotes' => $post->downvotes,
            'score' => $post->score,
            'userVote' => $userVote,
        ]);
    }

    /**
     * Generate AI summary for a post (AJAX endpoint)
     * 
     * @param ForumPost $post
     * @param GeminiService $geminiService
     * @return \Illuminate\Http\JsonResponse
     */
    public function summarize(ForumPost $post, GeminiService $geminiService)
    {
        \Log::info('Summarize method called for post: ' . $post->id);
        
        // Check if AI is configured
        if (!$geminiService->isConfigured()) {
            \Log::warning('Gemini not configured');
            return response()->json([
                'success' => false,
                'message' => 'L\'IA n\'est pas configurée. Veuillez ajouter votre clé API Gemini.',
            ], 503);
        }

        try {
            \Log::info('Calling geminiService->summarize');
            // Generate summary
            $summary = $geminiService->summarize($post->body, 250);
            \Log::info('Summary result: ' . ($summary ? 'success' : 'null'));

            if (!$summary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de générer le résumé. Veuillez réessayer.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'summary' => $summary,
            ]);

        } catch (\Exception $e) {
            \Log::error('Summarize exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du résumé: ' . $e->getMessage(),
            ], 500);
        }
    }
}
