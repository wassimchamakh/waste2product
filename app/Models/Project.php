<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty_level',
        'estimated_time',
        'impact_score',
        'photo',
        'status',
        'user_id',
        'category_id',
        'views_count',
        'favorites_count',
        'average_rating',
        'reviews_count'
    ];

    protected $casts = [
        'impact_score' => 'integer',
        'views_count' => 'integer',
        'favorites_count' => 'integer',
        'average_rating' => 'decimal:2',
        'reviews_count' => 'integer',
    ];

    // Relations
    public function steps() 
    {
        return $this->hasMany(ProjectStep::class)->orderBy('step_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessors & Mutators
    public function getDifficultyLabelAttribute()
    {
        return match($this->difficulty_level) {
            'facile' => 'Facile',
            'intermédiaire' => 'Intermédiaire', 
            'difficile' => 'Difficile',
            default => 'Non défini'
        };
    }

    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty_level) {
            'facile' => 'green',
            'intermédiaire' => 'orange',
            'difficile' => 'red',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'published' => 'Publié',
            'archived' => 'Archivé',
            default => 'Non défini'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'published' => 'green',
            'archived' => 'red',
            default => 'gray'
        };
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Helpers
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getStepsCount()
    {
        return $this->steps()->count();
    }

    public function isOwnedBy($userId)
    {
        return $this->user_id == $userId;
    }
}