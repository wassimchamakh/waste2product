<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * ForumPost Model
 * 
 * Represents a discussion post in the community forum.
 * Posts can receive votes, comments, and have moderation features.
 * 
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $user_id
 * @property int $views_count
 * @property int $upvotes
 * @property int $downvotes
 * @property bool $is_pinned
 * @property bool $is_locked
 * @property bool $is_spam
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ForumPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'views_count',
        'upvotes',
        'downvotes',
        'is_pinned',
        'is_locked',
        'is_spam',
        'status',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'is_spam' => 'boolean',
        'views_count' => 'integer',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
    ];

    /**
     * Spam keywords for detection (English & French)
     */
    protected static array $spamKeywords = [
        'spam', 'click here', 'buy now', 'limited offer', 'free money',
        'viagra', 'casino', 'lottery', 'winner', 'congratulations',
        'arnaque', 'gratuit argent', 'cliquez ici', 'gagnant'
    ];

    /**
     * Boot method - Auto-detect spam on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->is_spam = self::detectSpam($post->title . ' ' . $post->body);
        });
    }

    // ==========================================
    // Relationships
    // ==========================================

    /**
     * Post belongs to a user (author)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post has many comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ForumComment::class)->whereNull('parent_id')->latest();
    }

    /**
     * All comments including replies
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(ForumComment::class);
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
     * Published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_spam', false);
    }

    /**
     * Pinned posts first
     */
    public function scopePinned($query)
    {
        return $query->orderBy('is_pinned', 'desc');
    }

    /**
     * Most recent posts
     */
    public function scopeRecent($query)
    {
        return $query->latest();
    }

    /**
     * Most popular by votes
     */
    public function scopePopular($query)
    {
        return $query->orderByRaw('(upvotes - downvotes) DESC');
    }

    /**
     * Most commented
     */
    public function scopeMostCommented($query)
    {
        return $query->withCount('allComments')->orderBy('all_comments_count', 'desc');
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Calculate net score (upvotes - downvotes)
     */
    public function getScoreAttribute(): int
    {
        return $this->upvotes - $this->downvotes;
    }

    /**
     * Check if post is locked
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * Check if user can vote
     */
    public function userCanVote(int $userId): bool
    {
        return !$this->votes()->where('user_id', $userId)->exists();
    }

    /**
     * Get user's vote on this post (-1, 0, or 1)
     */
    public function getUserVote(int $userId): int
    {
        $vote = $this->votes()->where('user_id', $userId)->first();
        return $vote ? $vote->vote : 0;
    }

    /**
     * Static spam detection method
     */
    public static function detectSpam(string $content): bool
    {
        $lowerContent = strtolower($content);
        
        foreach (self::$spamKeywords as $keyword) {
            if (str_contains($lowerContent, $keyword)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get status badge CSS class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'published' => 'bg-success',
            'draft' => 'bg-warning',
            'archived' => 'bg-secondary',
            default => 'bg-info'
        };
    }

    /**
     * Check if user is the author
     */
    public function isAuthor(int $userId): bool
    {
        return $this->user_id === $userId;
    }

    /**
     * Get excerpt of body (first 200 characters)
     */
    public function getExcerptAttribute(): string
    {
        return strlen($this->body) > 200 
            ? substr($this->body, 0, 200) . '...' 
            : $this->body;
    }

    /**
     * Get reading time estimate (minutes)
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->body));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }
}
