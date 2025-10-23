<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserTutorialProgress extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'user_tutorial_progress';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'tutorial_id',
        'current_step',
        'total_steps_completed',
        'is_completed',
        'started_at',
        'completed_at',
        'last_accessed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_completed' => 'boolean',
        'current_step' => 'integer',
        'total_steps_completed' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    /**
     * Get the user that owns this progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tutorial this progress is for.
     */
    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class);
    }

    /**
     * Get all step completions for this progress.
     */
    public function stepCompletions(): HasMany
    {
        return $this->hasMany(UserStepCompletion::class, 'user_id', 'user_id')
                    ->where('tutorial_id', $this->tutorial_id);
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalSteps = $this->tutorial->steps()->count();
        if ($totalSteps === 0) {
            return 0;
        }
        return round(($this->total_steps_completed / $totalSteps) * 100, 1);
    }

    /**
     * Check if tutorial is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->started_at !== null && !$this->is_completed;
    }

    /**
     * Mark as started.
     */
    public function markAsStarted(): void
    {
        if (!$this->started_at) {
            $this->started_at = now();
            $this->save();
        }
    }

    /**
     * Update last accessed time.
     */
    public function updateLastAccessed(): void
    {
        $this->last_accessed_at = now();
        $this->save();
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted(): void
    {
        $this->is_completed = true;
        $this->completed_at = now();
        $this->save();
        
        // Increment tutorial completion count
        $this->tutorial->incrementCompletions();
    }

    /**
     * Sync total_steps_completed with actual UserStepCompletion records.
     */
    public function syncStepsCompleted(): void
    {
        $completedCount = UserStepCompletion::where('user_id', $this->user_id)
            ->where('tutorial_id', $this->tutorial_id)
            ->count();

        $this->total_steps_completed = $completedCount;
        
        // Check if tutorial is completed
        $totalSteps = $this->tutorial->steps()->count();
        if ($completedCount >= $totalSteps && $totalSteps > 0) {
            $this->markAsCompleted();
        } else {
            $this->save();
        }
    }

    /**
     * Complete a specific step and update progress.
     */
    public function completeStep(int $stepId): void
    {
        // Create or update step completion
        UserStepCompletion::updateOrCreate(
            [
                'user_id' => $this->user_id,
                'tutorial_id' => $this->tutorial_id,
                'step_id' => $stepId,
            ],
            [
                'completed_at' => now(),
            ]
        );

        // Sync the total count
        $this->syncStepsCompleted();
        
        // Update last accessed
        $this->updateLastAccessed();
    }
}
