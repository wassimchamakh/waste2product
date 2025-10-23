<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TutoComment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tuto_comments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tutorial_id',
        'user_id',
        'parent_comment_id',
        'comment_text',
        'rating',
        'helpful_count',
        'is_helpful_by_users',
        'status',
        'moderation_note',
        'moderated_by',
        'moderated_at',
        'is_edited',
        'edited_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tutorial_id' => 'integer',
        'user_id' => 'integer',
        'parent_comment_id' => 'integer',
        'rating' => 'integer',
        'helpful_count' => 'integer',
        'is_helpful_by_users' => 'array', // JSON cast
        'is_edited' => 'boolean',
        'moderated_at' => 'datetime',
        'edited_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['star_rating', 'time_ago'];

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Get the tutorial that this comment belongs to.
     * Many-to-One relationship
     */
    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class, 'tutorial_id');
    }

    /**
     * Get the user who wrote this comment.
     * Many-to-One relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent comment (for nested replies).
     * Many-to-One relationship (self-referential)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TutoComment::class, 'parent_comment_id');
    }

    /**
     * Get all replies to this comment.
     * One-to-Many relationship (self-referential)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TutoComment::class, 'parent_comment_id')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get approved replies only.
     */
    public function approvedReplies(): HasMany
    {
        return $this->hasMany(TutoComment::class, 'parent_comment_id')
                    ->where('status', 'Approved')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get the moderator who moderated this comment.
     * Many-to-One relationship
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * ============================================
     * SCOPES
     * ============================================
     */

    /**
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope to get only pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope to get only rejected comments.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    /**
     * Scope to get only flagged comments.
     */
    public function scopeFlagged($query)
    {
        return $query->where('status', 'Flagged');
    }

    /**
     * Scope to get only top-level comments (not replies).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_comment_id');
    }

    /**
     * Scope to get only comments with ratings.
     */
    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    /**
     * Scope to filter by tutorial.
     */
    public function scopeForTutorial($query, $tutorialId)
    {
        return $query->where('tutorial_id', $tutorialId);
    }

    /**
     * Scope to get most helpful comments.
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * ============================================
     * ACCESSORS & MUTATORS
     * ============================================
     */

    /**
     * Get star rating as string.
     */
    public function getStarRatingAttribute()
    {
        if (!$this->rating) {
            return null;
        }
        
        return str_repeat('⭐', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get human-readable time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get replies count.
     */
    public function getRepliesCountAttribute()
    {
        return $this->replies()->count();
    }

    /**
     * Get approved replies count.
     */
    public function getApprovedRepliesCountAttribute()
    {
        return $this->approvedReplies()->count();
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Check if comment was edited.
     */
    public function wasEdited(): bool
    {
        return $this->is_edited;
    }

    /**
     * Check if comment is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_comment_id);
    }

    /**
     * Check if comment is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    /**
     * Check if comment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if comment is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    /**
     * Check if comment is flagged.
     */
    public function isFlagged(): bool
    {
        return $this->status === 'Flagged';
    }

    /**
     * Approve the comment.
     */
    public function approve(?int $moderatorId = null): void
    {
        $this->status = 'Approved';
        $this->moderated_by = $moderatorId ?? auth()->id();
        $this->moderated_at = now();
        $this->save();

        // Update tutorial's average rating if this comment has a rating
        if ($this->rating) {
            $this->tutorial->updateAverageRating();
        }
    }

    /**
     * Reject the comment.
     */
    public function reject(?int $moderatorId = null, ?string $note = null): void
    {
        $this->status = 'Rejected';
        $this->moderated_by = $moderatorId ?? auth()->id();
        $this->moderated_at = now();
        if ($note) {
            $this->moderation_note = $note;
        }
        $this->save();
    }

    /**
     * Flag the comment for review.
     */
    public function flag(?string $note = null): void
    {
        $this->status = 'Flagged';
        if ($note) {
            $this->moderation_note = $note;
        }
        $this->save();
    }

    /**
     * Mark comment as helpful by a user.
     */
    public function markAsHelpful(int $userId): void
    {
        $helpfulUsers = $this->is_helpful_by_users ?? [];
        
        if (!in_array($userId, $helpfulUsers)) {
            $helpfulUsers[] = $userId;
            $this->is_helpful_by_users = $helpfulUsers;
            $this->helpful_count = count($helpfulUsers);
            $this->save();
        }
    }

    /**
     * Unmark comment as helpful by a user.
     */
    public function unmarkAsHelpful(int $userId): void
    {
        $helpfulUsers = $this->is_helpful_by_users ?? [];
        
        if (($key = array_search($userId, $helpfulUsers)) !== false) {
            unset($helpfulUsers[$key]);
            $this->is_helpful_by_users = array_values($helpfulUsers);
            $this->helpful_count = count($this->is_helpful_by_users);
            $this->save();
        }
    }

    /**
     * Check if user marked this comment as helpful.
     */
    public function isMarkedHelpfulBy(int $userId): bool
    {
        return in_array($userId, $this->is_helpful_by_users ?? []);
    }
}