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
        'maps_link',
        'latitude',
        'longitude',
        'max_participants',
        'price',
        'status',
        'image',
        'user_id',
        'program',
        'learning_objectives',
        'required_materials',
        'skill_level',
        'access_instructions',
        'parking_available',
        'accessible_pmr',
        'wifi_available',
        // Payment settings
        'requires_approval',
        'allow_installments',
        'installment_count',
        'cancellation_policy',
        'custom_cancellation_policy',
        'refund_schedule',
        'early_bird_price',
        'early_bird_deadline',
        'group_discount_enabled',
        'group_size',
        'group_price',
        'payment_deadline_hours',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'price' => 'decimal:2',
        'max_participants' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'program' => 'array',
        'parking_available' => 'boolean',
        'accessible_pmr' => 'boolean',
        'wifi_available' => 'boolean',
        // Payment settings casts
        'requires_approval' => 'boolean',
        'allow_installments' => 'boolean',
        'installment_count' => 'integer',
        'refund_schedule' => 'array',
        'early_bird_price' => 'decimal:2',
        'early_bird_deadline' => 'datetime',
        'group_discount_enabled' => 'boolean',
        'group_size' => 'integer',
        'group_price' => 'decimal:2',
        'payment_deadline_hours' => 'integer',
    ];

    // Relations
    
    /**
     * Organisateur de l'événement (alias pour user)
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Utilisateur créateur de l'événement
     */
    public function user()
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

    // ========== Payment Related Methods ==========

    /**
     * Check if event is free
     */
    public function isFree()
    {
        return $this->price == 0;
    }

    /**
     * Check if event is paid
     */
    public function isPaid()
    {
        return $this->price > 0;
    }

    /**
     * Check if early bird pricing is active
     */
    public function hasActiveEarlyBird()
    {
        return $this->early_bird_price > 0 
            && $this->early_bird_deadline 
            && now()->lte($this->early_bird_deadline);
    }

    /**
     * Get current event price (considering early bird)
     */
    public function getCurrentPrice()
    {
        if ($this->hasActiveEarlyBird()) {
            return (float) $this->early_bird_price;
        }

        return (float) $this->price;
    }

    /**
     * Get price with currency
     */
    public function getFormattedPrice()
    {
        $price = $this->getCurrentPrice();
        
        if ($price == 0) {
            return 'Gratuit';
        }

        return number_format($price, 2) . ' TND';
    }

    /**
     * Get early bird savings
     */
    public function getEarlyBirdSavings()
    {
        if (!$this->hasActiveEarlyBird()) {
            return 0;
        }

        return (float) $this->price - (float) $this->early_bird_price;
    }

    /**
     * Get cancellation policy label
     */
    public function getCancellationPolicyLabel()
    {
        return match($this->cancellation_policy) {
            'no_refund' => 'Aucun remboursement',
            'flexible' => 'Flexible - Remboursement complet jusqu\'à 24h avant',
            'moderate' => 'Modéré - 50% de remboursement jusqu\'à 7 jours avant',
            'strict' => 'Strict - 25% de remboursement jusqu\'à 30 jours avant',
            'custom' => 'Politique personnalisée',
            default => 'Non définie'
        };
    }

    /**
     * Get cancellation policy description
     */
    public function getCancellationPolicyDescription()
    {
        if ($this->cancellation_policy === 'custom' && $this->custom_cancellation_policy) {
            return $this->custom_cancellation_policy;
        }

        return match($this->cancellation_policy) {
            'no_refund' => 'Aucun remboursement ne sera accordé après l\'inscription.',
            'flexible' => 'Remboursement complet si vous annulez au moins 24 heures avant l\'événement.',
            'moderate' => 'Remboursement de 50% si vous annulez au moins 7 jours avant l\'événement.',
            'strict' => 'Remboursement de 25% si vous annulez au moins 30 jours avant l\'événement.',
            default => 'Veuillez contacter l\'organisateur pour plus d\'informations.'
        };
    }

    /**
     * Calculate total revenue from completed payments
     */
    public function getTotalRevenue()
    {
        return $this->participants()
            ->where('payment_status', 'completed')
            ->sum('amount_paid');
    }

    /**
     * Get count of paid participants
     */
    public function getPaidParticipantsCount()
    {
        return $this->participants()
            ->where('payment_status', 'completed')
            ->count();
    }

    /**
     * Get count of pending payments
     */
    public function getPendingPaymentsCount()
    {
        return $this->participants()
            ->where('payment_status', 'pending')
            ->count();
    }

    /**
     * Check if group discount is available
     */
    public function hasGroupDiscount()
    {
        return $this->group_discount_enabled 
            && $this->group_size > 0 
            && $this->group_price > 0;
    }

    /**
     * Check if participant count qualifies for group discount
     */
    public function qualifiesForGroupDiscount($participantCount)
    {
        return $this->hasGroupDiscount() && $participantCount >= $this->group_size;
    }

    /**
     * Get payment deadline date for a registration
     */
    public function getPaymentDeadline()
    {
        return now()->addHours($this->payment_deadline_hours ?? 24);
    }
}
