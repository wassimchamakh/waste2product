<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use App\Models\ForumPost;
use App\Models\ForumVote;
use App\Http\Requests\StoreForumCommentRequest;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ForumCommentController
 * 
 * Handles comment and reply operations with AJAX support.
 */
class ForumCommentController extends Controller
{
    /**
     * Store a new comment or reply (AJAX endpoint).
     * 
     * @param StoreForumCommentRequest $request
     * @param GeminiService $geminiService
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreForumCommentRequest $request, GeminiService $geminiService)
    {
        $this->authorize('create', ForumComment::class);

        // AI Spam Detection (if configured)
        $toxicityCheck = ['is_toxic' => false, 'reason' => null];
        $aiWarning = null;
        
        if ($geminiService->isConfigured()) {
            try {
                $toxicityCheck = $geminiService->checkToxicity($request->body);
                
                if ($toxicityCheck['is_toxic']) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Contenu inapproprié détecté',
                        'reason' => $toxicityCheck['reason'] ?? 'Votre commentaire contient du contenu potentiellement offensant ou spam.',
                        'is_toxic' => true,
                    ], 422);
                }
                
                $aiWarning = '✅ Commentaire vérifié par IA';
            } catch (\Exception $e) {
                Log::warning('AI toxicity check failed: ' . $e->getMessage());
                // Continue without AI check if it fails
            }
        }

        $comment = ForumComment::create([
            'body' => $request->body,
            'forum_post_id' => $request->forum_post_id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id, // null for top-level comments
        ]);

        // Load the user relationship
        $comment->load('user');

        // Update user's comment count
        Auth::user()->increment('comments_count');

        // Award reputation for commenting
        Auth::user()->increment('reputation', 1);
        Auth::user()->updateBadge();

        // Check if AJAX request
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès !',
                'ai_warning' => $aiWarning,
                'comment' => [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->avatar ?? asset('images/default-avatar.png'),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'upvotes' => $comment->upvotes,
                    'downvotes' => $comment->downvotes,
                    'score' => $comment->score,
                    'is_spam' => $comment->is_spam,
                    'is_best_answer' => $comment->is_best_answer,
                    'parent_id' => $comment->parent_id,
                ],
                'warning' => $comment->is_spam ? 'Votre commentaire a été marqué pour modération.' : null,
            ], 201);
        }

        return back()->with('success', '✅ Commentaire ajouté !');
    }

    /**
     * Update the specified comment (AJAX endpoint).
     * 
     * @param Request $request
     * @param ForumComment $comment
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ForumComment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'body' => 'required|string|min:5|max:5000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire mis à jour !',
                'comment' => [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'updated_at' => $comment->updated_at->diffForHumans(),
                ],
            ]);
        }

        return back()->with('success', '✅ Commentaire mis à jour !');
    }

    /**
     * Remove the specified comment (AJAX endpoint).
     * 
     * @param ForumComment $comment
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, ForumComment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        // Decrement user's comment count
        $comment->user->decrement('comments_count');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé.',
            ]);
        }

        return back()->with('success', '✅ Commentaire supprimé.');
    }

    /**
     * Handle voting on a comment (AJAX endpoint).
     * 
     * @param Request $request
     * @param ForumComment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function vote(Request $request, ForumComment $comment)
    {
        $this->authorize('vote', $comment);

        $request->validate([
            'vote' => 'required|in:1,-1',
        ]);

        $voteValue = (int) $request->vote;

        // Check if user already voted
        $existingVote = ForumVote::where('user_id', Auth::id())
            ->where('votable_type', ForumComment::class)
            ->where('votable_id', $comment->id)
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
                'votable_type' => ForumComment::class,
                'votable_id' => $comment->id,
                'vote' => $voteValue,
            ]);
            $userVote = $voteValue;
        }

        // Refresh comment to get updated vote counts
        $comment->refresh();

        return response()->json([
            'success' => true,
            'upvotes' => $comment->upvotes,
            'downvotes' => $comment->downvotes,
            'score' => $comment->score,
            'userVote' => $userVote,
        ]);
    }

    /**
     * Mark a comment as the best answer (AJAX endpoint).
     * 
     * @param Request $request
     * @param ForumComment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsBestAnswer(Request $request, ForumComment $comment)
    {
        $this->authorize('markAsBestAnswer', $comment);

        $comment->markAsBestAnswer();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '✅ Marqué comme meilleure réponse !',
            ]);
        }

        return back()->with('success', '✅ Marqué comme meilleure réponse !');
    }
}
