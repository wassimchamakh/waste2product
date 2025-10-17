<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Tutorial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tutorials';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'thumbnail_image',
        'intro_video_url',
        'difficulty_level',
        'category',
        'estimated_duration',
        'views_count',
        'completion_count',
        'average_rating',
        'total_ratings',
        'prerequisites',
        'learning_outcomes',
        'tags',
        'status',
        'is_featured',
        'created_by',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'completion_count' => 'integer',
        'average_rating' => 'decimal:2',
        'total_ratings' => 'integer',
        'estimated_duration' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['thumbnail_url', 'formatted_duration', 'completion_rate'];

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title when creating
        static::creating(function ($tutorial) {
            if (empty($tutorial->slug)) {
                // Use maxLength of 255 to avoid truncation
                $slug = Str::slug($tutorial->title, '-', null, ['maxLength' => 255]);
                
                // Ensure unique slug
                $count = 1;
                $originalSlug = $slug;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                
                $tutorial->slug = $slug;
            }
        });

        // Update slug when title changes (but keep existing slug if title hasn't changed)
        // DISABLED: Don't regenerate slug on update to avoid conflicts
        // Users can manually change slug if needed
        /*
        static::updating(function ($tutorial) {
            if ($tutorial->isDirty('title')) {
                // Use maxLength of 255 to avoid truncation
                $slug = Str::slug($tutorial->title, '-', null, ['maxLength' => 255]);
                
                // Ensure unique slug (excluding current tutorial)
                $count = 1;
                $originalSlug = $slug;
                while (static::where('slug', $slug)->where('id', '!=', $tutorial->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                
                $tutorial->slug = $slug;
            }
        });
        */
    }

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Get all steps for this tutorial.
     * One-to-Many relationship
     */
    public function steps(): HasMany
    {
        return $this->hasMany(TutoStep::class, 'tutorial_id')
                    ->orderBy('step_number', 'asc');
    }

    /**
     * Get all comments for this tutorial.
     * One-to-Many relationship
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TutoComment::class, 'tutorial_id')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get only approved comments.
     */
    public function approvedComments(): HasMany
    {
        return $this->hasMany(TutoComment::class, 'tutorial_id')
                    ->where('status', 'Approved')
                    ->whereNull('parent_comment_id') // Only top-level comments
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get the user who created this tutorial.
     * Many-to-One relationship
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all user progress records for this tutorial.
     * One-to-Many relationship
     */
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserTutorialProgress::class, 'tutorial_id');
    }

    /**
     * Get user progress for the current authenticated user.
     */
    public function myProgress(): ?UserTutorialProgress
    {
        if (!auth()->check()) {
            return null;
        }
        
        return $this->userProgress()
            ->where('user_id', auth()->id())
            ->first();
    }

    /**
     * ============================================
     * SCOPES
     * ============================================
     */

    /**
     * Scope to get only published tutorials.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Scope to get only draft tutorials.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    /**
     * Scope to get only archived tutorials.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'Archived');
    }

    /**
     * Scope to get only featured tutorials.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by difficulty level.
     */
    public function scopeDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to search by title, description, or tags.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('tags', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to order by popularity (views).
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    /**
     * Scope to order by rating.
     */
    public function scopeTopRated($query)
    {
        return $query->where('total_ratings', '>', 0)
                    ->orderBy('average_rating', 'desc');
    }

    /**
     * Scope to order by most recent.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * ============================================
     * ACCESSORS & MUTATORS
     * ============================================
     */

    /**
     * Get the tutorial's tags as an array.
     */
    public function getTagsArrayAttribute()
    {
        return $this->tags ? array_map('trim', explode(',', $this->tags)) : [];
    }

    /**
     * Get the full URL for thumbnail image.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_image) {
            // If it's a full URL (external image)
            if (filter_var($this->thumbnail_image, FILTER_VALIDATE_URL)) {
                return $this->thumbnail_image;
            }
            
            // Check if it starts with "tutorials/" (storage path format)
            if (str_starts_with($this->thumbnail_image, 'tutorials/')) {
                return asset('storage/' . $this->thumbnail_image);
            }
            
            // Otherwise, it's just a filename in public/uploads/tutorials/
            return asset('uploads/tutorials/' . $this->thumbnail_image);
        }
        
        // Default image
        return asset('images/default-tutorial.jpg');
    }

    /**
     * Get human-readable duration.
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->estimated_duration < 60) {
            return $this->estimated_duration . ' minutes';
        }
        
        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;
        
        return $hours . 'h ' . ($minutes > 0 ? $minutes . 'min' : '');
    }

    /**
     * Get completion rate percentage.
     */
    public function getCompletionRateAttribute()
    {
        if ($this->views_count == 0) {
            return 0;
        }
        
        return round(($this->completion_count / $this->views_count) * 100, 1);
    }

    /**
     * Get star rating as HTML or string.
     */
    public function getStarRatingAttribute()
    {
        $fullStars = floor($this->average_rating);
        $halfStar = ($this->average_rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        return str_repeat('⭐', $fullStars) . 
               ($halfStar ? '⭐' : '') . 
               str_repeat('☆', $emptyStars);
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment completion count.
     */
    public function incrementCompletions()
    {
        $this->increment('completion_count');
    }

    /**
     * Update average rating based on approved comments.
     */
    public function updateAverageRating()
    {
        $ratings = $this->comments()
                        ->where('status', 'Approved')
                        ->whereNotNull('rating')
                        ->get();

        $this->total_ratings = $ratings->count();
        $this->average_rating = $ratings->count() > 0 
            ? round($ratings->avg('rating'), 2)
            : 0;
        
        $this->save();
    }

    /**
     * Check if tutorial is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'Published';
    }

    /**
     * Check if tutorial is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'Draft';
    }

    /**
     * Check if tutorial is archived.
     */
    public function isArchived(): bool
    {
        return $this->status === 'Archived';
    }

    /**
     * Publish the tutorial.
     */
    public function publish()
    {
        $this->status = 'Published';
        $this->published_at = now();
        $this->save();
    }

    /**
     * Unpublish the tutorial (set to draft).
     */
    public function unpublish()
    {
        $this->status = 'Draft';
        $this->save();
    }

    /**
     * Archive the tutorial.
     */
    public function archive()
    {
        $this->status = 'Archived';
        $this->save();
    }

    /**
     * Get total steps count.
     */
    public function getTotalStepsAttribute()
    {
        return $this->steps()->count();
    }

    /**
     * Get total comments count (approved only).
     */
    public function getTotalCommentsAttribute()
    {
        return $this->approvedComments()->count();
    }
}