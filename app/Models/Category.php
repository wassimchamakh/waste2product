<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'image',
        'certifications',
        'tips',
    ];

    protected $casts = [
        'certifications' => 'array',
    ];

    // Relation avec Wastes (One-to-Many)
    public function dechets()
    {
        return $this->hasMany(Dechet::class);
    }

    public function projects()
{
    return $this->hasMany(Project::class, 'category_id');
}

    // Helper pour compter les déchets
    public function getDechetsCountAttribute()
    {
        return $this->dechets()->count();
    }

    // Helper pour obtenir les déchets disponibles
    public function availableDechets()
    {
        return $this->dechets()->where('status', 'available')->where('is_active', true);
    }

}
