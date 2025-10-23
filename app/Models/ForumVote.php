<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ForumVote Model
 * 
 * Represents an upvote or downvote on a post or comment.
 * Uses polymorphic relations to support voting on multiple types.
 * 
 * @property int $id
 * @property int $user_id
 * @property int $votable_id
 * @property string $votable_type
 * @property int $vote (1 for upvote, -1 for downvote)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ForumVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'votable_id',
        'votable_type',
        'vote',
    ];

    protected $casts = [
        'vote' => 'integer',
    ];

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * Vote belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relation - votable can be Post or Comment
     */
    public function votable(): MorphTo
    {
        return $this->morphTo();
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Check if this is an upvote
     */
    public function isUpvote(): bool
    {
        return $this->vote === 1;
    }

    /**
     * Check if this is a downvote
     */
    public function isDownvote(): bool
    {
        return $this->vote === -1;
    }

    /**
     * Boot method to update vote counts and reputation
     */
    protected static function boot()
    {
        parent::boot();

        // When vote is created
        static::created(function ($vote) {
            $vote->updateVoteCounts();
            $vote->updateAuthorReputation();
        });

        // When vote is updated (changed from upvote to downvote or vice versa)
        static::updated(function ($vote) {
            $vote->updateVoteCounts();
            $vote->updateAuthorReputation();
        });

        // When vote is deleted
        static::deleted(function ($vote) {
            $vote->updateVoteCounts();
            $vote->updateAuthorReputation();
        });
    }

    /**
     * Update the vote counts on the votable model
     */
    protected function updateVoteCounts(): void
    {
        $votable = $this->votable;
        
        if ($votable) {
            $votable->upvotes = $votable->votes()->where('vote', 1)->count();
            $votable->downvotes = $votable->votes()->where('vote', -1)->count();
            $votable->save();
        }
    }

    /**
     * Update author's reputation based on vote
     */
    protected function updateAuthorReputation(): void
    {
        $votable = $this->votable;
        
        if ($votable && isset($votable->user)) {
            $author = $votable->user;
            
            // Recalculate total reputation from all votes
            $totalReputation = 0;
            
            // Posts votes (+5 upvote, -2 downvote)
            $posts = ForumPost::where('user_id', $author->id)->get();
            foreach ($posts as $post) {
                $totalReputation += ($post->upvotes * 5) - ($post->downvotes * 2);
            }
            
            // Comments votes (+2 upvote, -1 downvote)
            $comments = ForumComment::where('user_id', $author->id)->get();
            foreach ($comments as $comment) {
                $totalReputation += ($comment->upvotes * 2) - ($comment->downvotes * 1);
            }
            
            // Best answers bonus (already added)
            $totalReputation += $author->best_answers_count * 15;
            
            $author->reputation = max(0, $totalReputation); // Can't go below 0
            $author->save();
            $author->updateBadge();
        }
    }
}
