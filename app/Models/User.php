<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'avatar',
        'is_admin',
        'total_co2_saved',
        'projects_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'total_co2_saved' => 'decimal:2',
        ];
    }
    public function dechets()
    {
        return $this->hasMany(Dechet::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all tutorials created by this user.
     */
    public function createdTutorials()
    {
        return $this->hasMany(Tutorial::class, 'created_by');
    }

    /**
     * Get all comments made by this user.
     */
    public function tutorialComments()
    {
        return $this->hasMany(TutoComment::class, 'user_id');
    }

    /**
     * Get all tutorial progress records for this user.
     */
    public function tutorialProgress()
    {
        return $this->hasMany(UserTutorialProgress::class, 'user_id');
    }

    /**
     * Get all step completions for this user.
     */
    public function stepCompletions()
    {
        return $this->hasMany(UserStepCompletion::class, 'user_id');
    }

    /**
     * Get tutorials in progress for this user.
     */
    public function tutorialsInProgress()
    {
        return $this->hasManyThrough(
            Tutorial::class,
            UserTutorialProgress::class,
            'user_id',
            'id',
            'id',
            'tutorial_id'
        )->where('is_completed', false);
    }

    /**
     * Get completed tutorials for this user.
     */
    public function completedTutorials()
    {
        return $this->hasManyThrough(
            Tutorial::class,
            UserTutorialProgress::class,
            'user_id',
            'id',
            'id',
            'tutorial_id'
        )->where('is_completed', true);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    
}
