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
        'is_active',
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views_count' => 'integer',
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
}
