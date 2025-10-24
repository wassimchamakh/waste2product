<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * ForumComment Model
 * 
 * Represents a comment or reply on a forum post.
 * Supports nested replies, voting, and spam detection.
 * 
 * @property int $id
 * @property string $body
 * @property int $forum_post_id
 * @property int $user_id
 * @property int|null $parent_id
 * @property int $upvotes
 * @property int $downvotes
 * @property bool $is_spam
 * @property bool $is_best_answer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ForumComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'body',
        'forum_post_id',
        'user_id',
        'parent_id',
        'upvotes',
        'downvotes',
        'is_spam',
        'is_best_answer',
    ];

    protected $casts = [
        'is_spam' => 'boolean',
        'is_best_answer' => 'boolean',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
    ];

    /**
     * Boot method - Auto-detect spam
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $comment->is_spam = ForumPost::detectSpam($comment->body);
        });
    }

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * Comment belongs to a post
     */
    public function forumPost(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class);
    }

    /**
     * Comment belongs to a user (author)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comment may have a parent (for nested replies)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumComment::class, 'parent_id');
    }

    /**
     * Comment may have many replies
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'parent_id')->latest();
    }

    /**
     * Polymorphic relation to votes
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'votable');
    }

    // ==========================================
    // Scopes
    // ==========================================

    /**
     * Top-level comments only (no replies)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Non-spam comments
     */
    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    /**
     * Best answers only
     */
    public function scopeBestAnswers($query)
    {
        return $query->where('is_best_answer', true);
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Calculate net score
     */
    public function getScoreAttribute(): int
    {
        return $this->upvotes - $this->downvotes;
    }

    /**
     * Check if this is a reply
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Check if user can vote
     */
    public function userCanVote(int $userId): bool
    {
        return !$this->votes()->where('user_id', $userId)->exists();
    }

    /**
     * Get user's vote on this comment
     */
    public function getUserVote(int $userId): int
    {
        $vote = $this->votes()->where('user_id', $userId)->first();
        return $vote ? $vote->vote : 0;
    }

    /**
     * Mark as best answer (only one per post)
     */
    public function markAsBestAnswer(): void
    {
        // Unmark other comments on same post
        self::where('forum_post_id', $this->forum_post_id)
            ->where('id', '!=', $this->id)
            ->update(['is_best_answer' => false]);
        
        $this->update(['is_best_answer' => true]);
        
        // Award reputation to comment author
        $this->user->increment('best_answers_count');
        $this->user->increment('reputation', 15); // +15 rep for best answer
        $this->user->updateBadge();
    }

    /**
     * Check if user is the author
     */
    public function isAuthor(int $userId): bool
    {
        return $this->user_id === $userId;
    }
}
