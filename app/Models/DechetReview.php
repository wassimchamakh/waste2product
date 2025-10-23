<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DechetReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dechet_id',
        'rating',
        'comment',
        'is_verified_transaction',
    ];

    protected $casts = [
        'is_verified_transaction' => 'boolean',
        'rating' => 'integer',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dechet()
    {
        return $this->belongsTo(Dechet::class);
    }

    // Validation
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = max(1, min(5, $value));
    }
}
