<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'date_start',
        'date_end',
        'location',
        'max_participants',
        'price',
        'status',
        'image',
        'user_id',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'price' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    // Relations
    
    /**
     * Organisateur de l'événement
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Participants inscrits
     */
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Participants confirmés uniquement
     */
    public function confirmedParticipants()
    {
        return $this->hasMany(Participant::class)
                    ->where('attendance_status', 'confirmed');
    }

    /**
     * Participants présents
     */
    public function attendedParticipants()
    {
        return $this->hasMany(Participant::class)
                    ->where('attendance_status', 'attended');
    }

    // Scopes

    /**
     * Événements publiés
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Événements à venir
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_start', '>', now())
                    ->where('status', 'published')
                    ->orderBy('date_start', 'asc');
    }

    /**
     * Événements passés
     */
    public function scopePast($query)
    {
        return $query->where('date_end', '<', now())
                    ->orderBy('date_start', 'desc');
    }

    /**
     * Événements en cours
     */
    public function scopeOngoing($query)
    {
        return $query->where('date_start', '<=', now())
                    ->where('date_end', '>=', now())
                    ->where('status', 'ongoing');
    }

    /**
     * Événements par type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Événements par localisation
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'LIKE', "%{$location}%");
    }

    /**
     * Événements gratuits
     */
    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    // Accessors & Helpers

    /**
     * Obtenir le nombre de participants inscrits
     */
    public function getParticipantsCountAttribute()
    {
        return $this->participants()
                    ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
                    ->count();
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function isFull()
    {
        return $this->participants_count >= $this->max_participants;
    }

    /**
     * Obtenir les places restantes
     */
    public function getRemainingSeatsAttribute()
    {
        return max(0, $this->max_participants - $this->participants_count);
    }

    /**
     * Obtenir le pourcentage de remplissage
     */
    public function getFillPercentageAttribute()
    {
        if ($this->max_participants == 0) return 0;
        return round(($this->participants_count / $this->max_participants) * 100);
    }

    /**
     * Vérifier si l'événement est passé
     */
    public function isPast()
    {
        return $this->date_end < now();
    }

    /**
     * Vérifier si l'événement est à venir
     */
    public function isUpcoming()
    {
        return $this->date_start > now();
    }

    /**
     * Vérifier si l'événement est en cours
     */
    public function isOngoing()
    {
        return $this->date_start <= now() && $this->date_end >= now();
    }

    /**
     * Obtenir le statut de l'événement avec style
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'published' => 'bg-success',
            'ongoing' => 'bg-warning',
            'completed' => 'bg-neutral',
            'cancelled' => 'bg-red-500',
            'draft' => 'bg-gray-400',
            default => 'bg-gray-400'
        };
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'published' => 'Publié',
            'ongoing' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            'draft' => 'Brouillon',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir la classe CSS du badge type
     */
    public function getTypeBadgeClass()
    {
        return match($this->type) {
            'workshop' => 'bg-secondary',
            'collection' => 'bg-success',
            'training' => 'bg-primary',
            'repair_cafe' => 'bg-accent',
            default => 'bg-gray-400'
        };
    }

    /**
     * Obtenir le label du type
     */
    public function getTypeLabel()
    {
        return match($this->type) {
            'workshop' => 'Atelier',
            'collection' => 'Collecte',
            'training' => 'Formation',
            'repair_cafe' => 'Repair Café',
            default => 'Événement'
        };
    }

    /**
     * Obtenir l'icône du type
     */
    public function getTypeIcon()
    {
        return match($this->type) {
            'workshop' => 'fas fa-tools',
            'collection' => 'fas fa-recycle',
            'training' => 'fas fa-graduation-cap',
            'repair_cafe' => 'fas fa-coffee',
            default => 'fas fa-calendar'
        };
    }

    /**
     * Obtenir la date formatée
     */
    public function getFormattedDateAttribute()
    {
        return $this->date_start->locale('fr')->isoFormat('dddd D MMMM YYYY, HH:mm');
    }

    /**
     * Obtenir la durée en heures
     */
    public function getDurationAttribute()
    {
        return $this->date_start->diffInHours($this->date_end);
    }

    /**
     * Obtenir le countdown (dans combien de temps)
     */
    public function getCountdownAttribute()
    {
        if ($this->isPast()) {
            return 'Terminé';
        }
        
        if ($this->isOngoing()) {
            return 'En cours';
        }

        $diff = now()->diff($this->date_start);
        
        if ($diff->days > 0) {
            return "Dans {$diff->days} jour" . ($diff->days > 1 ? 's' : '');
        }
        
        if ($diff->h > 0) {
            return "Dans {$diff->h} heure" . ($diff->h > 1 ? 's' : '');
        }
        
        return "Dans {$diff->i} minute" . ($diff->i > 1 ? 's' : '');
    }

    /**
     * Obtenir l'URL de l'image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('uploads/events/' . $this->image);
        }
        return asset('images/event-placeholder.jpg');
    }

    /**
     * Vérifier si un utilisateur est inscrit
     */
    public function isUserRegistered($userId)
    {
        return $this->participants()
                    ->where('user_id', $userId)
                    ->whereIn('attendance_status', ['registered', 'confirmed', 'attended'])
                    ->exists();
    }

    /**
     * Obtenir le participant d'un utilisateur
     */
    public function getUserParticipation($userId)
    {
        return $this->participants()
                    ->where('user_id', $userId)
                    ->first();
    }

    /**
     * Vérifier si l'utilisateur est l'organisateur
     */
    public function isOrganizer($userId)
    {
        return $this->user_id == $userId;
    }
}