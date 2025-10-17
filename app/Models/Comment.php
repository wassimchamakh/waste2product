<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Liste de mots interdits (anglais et français)
    protected static $badWords = [
        // Anglais
        'fuck', 'shit', 'bitch', 'asshole', 'bastard', 'damn', 'crap', 'dick', 'piss', 'slut', 'whore', 'jerk', 'idiot', 'stupid', 'suck',
        // Français
        'merde', 'salope', 'connard', 'con', 'pute', 'enculé', 'batard', 'abruti', 'débile', 'nul', 'chiant', 'bordel', 'foutre', 'ta gueule', 'fdp',
    ];

    protected $fillable = [
        'user_id',
        'project_id',
        'content',
    ];

    // Mutateur pour filtrer le contenu
    public function getFilteredContentAttribute()
    {
        $content = $this->content;
        foreach (self::$badWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            $content = preg_replace($pattern, '****', $content);
        }
        return $content;
    }

    // Auteur du commentaire
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Projet associé au commentaire
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
