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
        'sentiment_label',
        'sentiment_score',
        'sentiment_confidence',
        'sentiment_details',
        'feedback_themes',
        'sentiment_analyzed_at',
        // Payment fields
        'payment_status',
        'amount_paid',
        'amount_refunded',
        'payment_method',
        'payment_provider_id',
        'payment_intent_id',
        'payment_completed_at',
        'refund_requested_at',
        'refund_completed_at',
        'refund_reason',
        'refund_transaction_id',
        'invoice_number',
        'invoice_sent_at',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'email_sent' => 'boolean',
        'rating' => 'integer',
        'sentiment_score' => 'float',
        'sentiment_confidence' => 'float',
        'sentiment_details' => 'array',
        'feedback_themes' => 'array',
        'sentiment_analyzed_at' => 'datetime',
        // Payment casts
        'amount_paid' => 'decimal:2',
        'amount_refunded' => 'decimal:2',
        'payment_completed_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refund_completed_at' => 'datetime',
        'invoice_sent_at' => 'datetime',
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

    // ========== Payment Related Methods ==========

    /**
     * Check if payment is required for this participant
     */
    public function requiresPayment()
    {
        return $this->payment_status !== 'not_required';
    }

    /**
     * Check if payment is completed
     */
    public function hasCompletedPayment()
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function hasPaymentPending()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment has failed
     */
    public function hasPaymentFailed()
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if participant has been refunded
     */
    public function isRefunded()
    {
        return in_array($this->payment_status, ['refunded', 'partially_refunded']);
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeClass()
    {
        return match($this->payment_status) {
            'not_required' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-purple-100 text-purple-800',
            'partially_refunded' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabel()
    {
        return match($this->payment_status) {
            'not_required' => 'Gratuit',
            'pending' => 'En attente de paiement',
            'completed' => 'Payé',
            'failed' => 'Échec du paiement',
            'refunded' => 'Remboursé',
            'partially_refunded' => 'Partiellement remboursé',
            default => 'Inconnu'
        };
    }

    /**
     * Get payment status icon
     */
    public function getPaymentStatusIcon()
    {
        return match($this->payment_status) {
            'not_required' => 'fas fa-gift',
            'pending' => 'fas fa-clock',
            'completed' => 'fas fa-check-circle',
            'failed' => 'fas fa-times-circle',
            'refunded', 'partially_refunded' => 'fas fa-undo',
            default => 'fas fa-question-circle'
        };
    }

    /**
     * Check if refund can be requested
     */
    public function canRequestRefund()
    {
        // Must have completed payment
        if (!$this->hasCompletedPayment()) {
            return false;
        }

        // Already refunded
        if ($this->isRefunded()) {
            return false;
        }

        // Event must not have started yet
        if ($this->event && now()->gte($this->event->date_start)) {
            return false;
        }

        return true;
    }

    /**
     * Get refund amount available based on cancellation policy
     */
    public function getAvailableRefundAmount()
    {
        if (!$this->event || !$this->amount_paid) {
            return 0;
        }

        $daysUntilEvent = now()->diffInDays($this->event->date_start, false);

        // If event is in the past, no refund
        if ($daysUntilEvent < 0) {
            return 0;
        }

        // Apply cancellation policy
        switch ($this->event->cancellation_policy) {
            case 'no_refund':
                return 0;

            case 'flexible':
                // Full refund if more than 24 hours before event
                return $daysUntilEvent >= 1 ? $this->amount_paid : 0;

            case 'moderate':
                // 50% refund up to 7 days before
                return $daysUntilEvent >= 7 ? $this->amount_paid * 0.5 : 0;

            case 'strict':
                // 25% refund up to 30 days before
                return $daysUntilEvent >= 30 ? $this->amount_paid * 0.25 : 0;

            case 'custom':
                // Use custom refund schedule
                if ($this->event->refund_schedule) {
                    $schedule = is_array($this->event->refund_schedule) 
                        ? $this->event->refund_schedule 
                        : json_decode($this->event->refund_schedule, true);
                    
                    foreach ($schedule as $days => $percent) {
                        $daysValue = (int) str_replace('_days', '', $days);
                        if ($daysUntilEvent >= $daysValue) {
                            return $this->amount_paid * ($percent / 100);
                        }
                    }
                }
                return 0;

            default:
                return 0;
        }
    }
}
