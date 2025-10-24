<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DechetFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dechet_id',
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
}
