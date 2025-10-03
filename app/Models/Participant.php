<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'registration_date',
        'attendance_status',
        'feedback',
        'rating',
        'email_sent',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'email_sent' => 'boolean',
        'rating' => 'integer',
    ];

    // Relations

    /**
     * Événement concerné
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Utilisateur participant
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes

    /**
     * Participants confirmés
     */
    public function scopeConfirmed($query)
    {
        return $query->where('attendance_status', 'confirmed');
    }

    /**
     * Participants présents
     */
    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'attended');
    }

    /**
     * Participants absents
     */
    public function scopeAbsent($query)
    {
        return $query->where('attendance_status', 'absent');
    }

    /**
     * Participants en attente
     */
    public function scopeRegistered($query)
    {
        return $query->where('attendance_status', 'registered');
    }

    /**
     * Participants annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('attendance_status', 'cancelled');
    }

    /**
     * Avec feedback
     */
    public function scopeWithFeedback($query)
    {
        return $query->whereNotNull('feedback');
    }

    /**
     * Avec note
     */
    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    // Accessors & Helpers

    /**
     * Obtenir la classe CSS du badge statut
     */
    public function getStatusBadgeClass()
    {
        return match($this->attendance_status) {
            'registered' => 'bg-warning',
            'confirmed' => 'bg-success',
            'attended' => 'bg-primary',
            'absent' => 'bg-red-500',
            'cancelled' => 'bg-gray-400',
            default => 'bg-gray-400'
        };
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusLabel()
    {
        return match($this->attendance_status) {
            'registered' => 'En attente',
            'confirmed' => 'Confirmé',
            'attended' => 'Présent',
            'absent' => 'Absent',
            'cancelled' => 'Annulé',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir l'icône du statut
     */
    public function getStatusIcon()
    {
        return match($this->attendance_status) {
            'registered' => 'fas fa-clock',
            'confirmed' => 'fas fa-check-circle',
            'attended' => 'fas fa-user-check',
            'absent' => 'fas fa-user-times',
            'cancelled' => 'fas fa-ban',
            default => 'fas fa-question-circle'
        };
    }

    /**
     * Vérifier si le participant a laissé un feedback
     */
    public function hasFeedback()
    {
        return !empty($this->feedback);
    }

    /**
     * Vérifier si le participant a noté
     */
    public function hasRating()
    {
        return !is_null($this->rating);
    }

    /**
     * Obtenir les étoiles de notation (HTML)
     */
    public function getRatingStarsAttribute()
    {
        if (!$this->rating) return '';
        
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating 
                ? '<i class="fas fa-star text-warning"></i>' 
                : '<i class="far fa-star text-gray-300"></i>';
        }
        return $stars;
    }

    /**
     * Obtenir la date d'inscription formatée
     */
    public function getFormattedRegistrationDateAttribute()
    {
        return $this->registration_date->locale('fr')->isoFormat('D MMM YYYY, HH:mm');
    }
}