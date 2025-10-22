<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'event_id',
        'user_id',
        'original_transaction_id',
        'refund_amount',
        'refund_type',
        'status',
        'reason',
        'reason_category',
        'admin_notes',
        'rejection_reason',
        'refund_transaction_id',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // ========== Relationships ==========

    /**
     * Get the participant
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who requested refund
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the original payment transaction
     */
    public function originalTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'original_transaction_id');
    }

    /**
     * Get the refund transaction (if processed)
     */
    public function refundTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'refund_transaction_id');
    }

    /**
     * Get the admin/organizer who processed the refund
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // ========== Scopes ==========

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for processing requests
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed requests
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by refund type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('refund_type', $type);
    }

    // ========== Helper Methods ==========

    /**
     * Check if request is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if request is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if request has failed
     */
    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if full refund
     */
    public function isFullRefund()
    {
        return $this->refund_type === 'full';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'processing' => 'En cours de traitement',
            'completed' => 'Remboursé',
            'failed' => 'Échec',
            default => 'Inconnu'
        };
    }

    /**
     * Get refund type label
     */
    public function getRefundTypeLabel()
    {
        return match($this->refund_type) {
            'full' => 'Remboursement complet',
            'partial' => 'Remboursement partiel',
            'policy_based' => 'Selon politique d\'annulation',
            default => 'Inconnu'
        };
    }

    /**
     * Get reason category label
     */
    public function getReasonCategoryLabel()
    {
        return match($this->reason_category) {
            'event_cancelled' => 'Événement annulé',
            'event_rescheduled' => 'Événement reporté',
            'personal_emergency' => 'Urgence personnelle',
            'cannot_attend' => 'Ne peut pas assister',
            'dissatisfied' => 'Insatisfait',
            'other' => 'Autre',
            default => 'Non spécifié'
        };
    }

    /**
     * Get formatted refund amount
     */
    public function getFormattedAmount()
    {
        return number_format($this->refund_amount, 2) . ' TND';
    }

    /**
     * Approve the refund request
     */
    public function approve($userId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $userId,
            'processed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Reject the refund request
     */
    public function reject($userId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'processed_by' => $userId,
            'processed_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($transactionId)
    {
        $this->update([
            'status' => 'completed',
            'refund_transaction_id' => $transactionId,
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($reason)
    {
        $this->update([
            'status' => 'failed',
            'admin_notes' => $reason,
        ]);
    }
}
