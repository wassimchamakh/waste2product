<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutoStep extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tuto_steps';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tutorial_id',
        'step_number',
        'title',
        'description',
        'content',
        'image_url',
        'video_url',
        'estimated_time',
        'tips',
        'common_mistakes',
        'required_materials',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tutorial_id' => 'integer',
        'step_number' => 'integer',
        'estimated_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['image_full_url', 'formatted_time'];

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Get the tutorial that owns this step.
     * Many-to-One relationship
     */
    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class, 'tutorial_id');
    }

    /**
     * ============================================
     * ACCESSORS & MUTATORS
     * ============================================
     */

    /**
     * Get the full URL for step image.
     */
    public function getImageFullUrlAttribute()
    {
        if ($this->image_url) {
            // If it's a full URL
            if (filter_var($this->image_url, FILTER_VALIDATE_URL)) {
                return $this->image_url;
            }
            // If it's a storage path
            return asset('storage/' . $this->image_url);
        }
        
        return null;
    }

    /**
     * Get human-readable time for this step.
     */
    public function getFormattedTimeAttribute()
    {
        if ($this->estimated_time < 60) {
            return $this->estimated_time . ' min';
        }
        
        $hours = floor($this->estimated_time / 60);
        $minutes = $this->estimated_time % 60;
        
        return $hours . 'h ' . ($minutes > 0 ? $minutes . 'min' : '');
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Check if step has video.
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * Check if step has image.
     */
    public function hasImage(): bool
    {
        return !empty($this->image_url);
    }

    /**
     * Check if step has tips.
     */
    public function hasTips(): bool
    {
        return !empty($this->tips);
    }

    /**
     * Check if step has common mistakes section.
     */
    public function hasCommonMistakes(): bool
    {
        return !empty($this->common_mistakes);
    }

    /**
     * Check if step has required materials.
     */
    public function hasRequiredMaterials(): bool
    {
        return !empty($this->required_materials);
    }

    /**
     * ============================================
     * SCOPES
     * ============================================
     */

    /**
     * Scope to order by step number.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_number', 'asc');
    }

    /**
     * Scope to get steps for a specific tutorial.
     */
    public function scopeForTutorial($query, $tutorialId)
    {
        return $query->where('tutorial_id', $tutorialId);
    }

    /**
     * ============================================
     * NAVIGATION METHODS
     * ============================================
     */

    /**
     * Get the next step.
     */
    public function nextStep()
    {
        return self::where('tutorial_id', $this->tutorial_id)
                   ->where('step_number', '>', $this->step_number)
                   ->orderBy('step_number', 'asc')
                   ->first();
    }

    /**
     * Get the previous step.
     */
    public function previousStep()
    {
        return self::where('tutorial_id', $this->tutorial_id)
                   ->where('step_number', '<', $this->step_number)
                   ->orderBy('step_number', 'desc')
                   ->first();
    }

    /**
     * Check if this is the first step.
     */
    public function isFirst(): bool
    {
        return $this->step_number === 1;
    }

    /**
     * Check if this is the last step.
     */
    public function isLast(): bool
    {
        $maxStep = self::where('tutorial_id', $this->tutorial_id)
                       ->max('step_number');
        
        return $this->step_number === $maxStep;
    }

    /**
     * Get step position (e.g., "Step 2 of 5").
     */
    public function getPositionAttribute()
    {
        $totalSteps = self::where('tutorial_id', $this->tutorial_id)->count();
        return "Step {$this->step_number} of {$totalSteps}";
    }

    /**
     * Get progress percentage for this step.
     */
    public function getProgressPercentageAttribute()
    {
        $totalSteps = self::where('tutorial_id', $this->tutorial_id)->count();
        
        if ($totalSteps === 0) {
            return 0;
        }
        
        return round(($this->step_number / $totalSteps) * 100, 1);
    }
}