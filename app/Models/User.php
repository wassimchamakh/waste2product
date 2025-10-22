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
        'reputation',
        'badge',
        'posts_count',
        'comments_count',
        'best_answers_count',
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

    /**
     * Relation avec les déchets
     */
    public function dechets()
    {
        return $this->hasMany(Dechet::class);
    }

    /**
     * Relation avec les projets
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Relation avec les commentaires
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    // ==========================================
    // Forum Relationships
    // ==========================================

    /**
     * User's forum posts
     */
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    /**
     * User's forum comments
     */
    public function forumComments()
    {
        return $this->hasMany(ForumComment::class);
    }

    /**
     * User's votes
     */
    public function votes()
    {
        return $this->hasMany(ForumVote::class);
    }

    // ==========================================
    // Reputation & Badge System
    // ==========================================

    /**
     * Update user's badge based on reputation
     */
    public function updateBadge(): void
    {
        $badge = match(true) {
            $this->reputation >= 1000 => 'platinum',
            $this->reputation >= 500 => 'gold',
            $this->reputation >= 100 => 'silver',
            $this->reputation >= 10 => 'bronze',
            default => null
        };

        if ($this->badge !== $badge) {
            $this->update(['badge' => $badge]);
        }
    }

    /**
     * Get badge display info
     */
    public function getBadgeInfo(): array
    {
        return match($this->badge) {
            'platinum' => [
                'name' => 'Platinum',
                'icon' => '💎',
                'color' => 'text-purple-600',
                'class' => 'bg-purple-100'
            ],
            'gold' => [
                'name' => 'Gold',
                'icon' => '🥇',
                'color' => 'text-yellow-600',
                'class' => 'bg-yellow-100'
            ],
            'silver' => [
                'name' => 'Silver',
                'icon' => '🥈',
                'color' => 'text-gray-600',
                'class' => 'bg-gray-100'
            ],
            'bronze' => [
                'name' => 'Bronze',
                'icon' => '🥉',
                'color' => 'text-orange-600',
                'class' => 'bg-orange-100'
            ],
            default => [
                'name' => 'Newcomer',
                'icon' => '🌱',
                'color' => 'text-green-600',
                'class' => 'bg-green-100'
            ]
        };
    }

    /**
     * Get reputation level name
     */
    public function getReputationLevelAttribute(): string
    {
        return match(true) {
            $this->reputation >= 1000 => 'Expert',
            $this->reputation >= 500 => 'Advanced',
            $this->reputation >= 100 => 'Intermediate',
            $this->reputation >= 10 => 'Beginner',
            default => 'Newcomer'
        };
    }
}