<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dechet extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'quantity',
        'location',
        'status',
        'photo',
        'contact_phone',
        'notes',
        'views_count',
        'average_rating',
        'reviews_count',
        'favorites_count',
        'is_active',
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'average_rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'favorites_count' => 'integer',
    ];

    // Relation avec Category (Many-to-One)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation avec User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec Reviews (One-to-Many)
    public function reviews()
    {
        return $this->hasMany(DechetReview::class);
    }

    // Relation avec Favorites (One-to-Many)
    public function favorites()
    {
        return $this->hasMany(DechetFavorite::class);
    }

    // Check if favorited by user
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    // Check if reviewed by user
    public function isReviewedBy($userId)
    {
        return $this->reviews()->where('user_id', $userId)->exists();
    }

    // Scopes pour filtrage
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'LIKE', "%{$location}%");
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    // Helpers
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'available' => 'bg-success',
            'reserved' => 'bg-warning',
            'collected' => 'bg-secondary',
            default => 'bg-info'
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'available' => 'Disponible',
            'reserved' => 'Réservé',
            'collected' => 'Collecté',
            default => 'Inconnu'
        };
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('uploads/dechets/' . $this->photo) : asset('images/placeholder.jpg');
    }

    // Update average rating
    public function updateRating()
    {
        $this->update([
            'average_rating' => $this->reviews()->avg('rating') ?? 0,
            'reviews_count' => $this->reviews()->count(),
        ]);
    }

    // Update favorites count
    public function updateFavoritesCount()
    {
        $this->update([
            'favorites_count' => $this->favorites()->count(),
        ]);
    }

    // Get rating stars HTML
    public function getRatingStarsHtml(): string
    {
        $rating = $this->average_rating;
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        $html = '';
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<i class="fas fa-star text-yellow-400"></i>';
        }
        if ($halfStar) {
            $html .= '<i class="fas fa-star-half-alt text-yellow-400"></i>';
        }
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<i class="far fa-star text-yellow-400"></i>';
        }
        return $html;
    }
}
