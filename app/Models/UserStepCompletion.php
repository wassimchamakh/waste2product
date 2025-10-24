<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStepCompletion extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'user_step_completions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'tutorial_id',
        'step_id',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that completed this step.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tutorial this step belongs to.
     */
    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class);
    }

    /**
     * Get the step details.
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(TutoStep::class, 'step_id');
    }
}
