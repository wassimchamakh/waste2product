<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'event_id',
        'user_id',
        'transaction_id',
        'provider_transaction_id',
        'type',
        'status',
        'amount',
        'fee_amount',
        'platform_fee',
        'organizer_amount',
        'currency',
        'payment_method',
        'payment_method_details',
        'payment_provider',
        'payment_intent_id',
        'charge_id',
        'provider_response',
        'description',
        'failure_reason',
        'failure_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'billing_address',
        'ip_address',
        'user_agent',
        'initiated_at',
        'completed_at',
        'failed_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'organizer_amount' => 'decimal:2',
        'payment_method_details' => 'array',
        'provider_response' => 'array',
        'initiated_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // ========== Relationships ==========

    /**
     * Get the participant that owns the transaction
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the event that owns the transaction
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get refund requests for this transaction
     */
    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class, 'original_transaction_id');
    }

    // ========== Scopes ==========

    /**
     * Scope for payment transactions
     */
    public function scopePayments($query)
    {
        return $query->where('type', 'payment');
    }

    /**
     * Scope for refund transactions
     */
    public function scopeRefunds($query)
    {
        return $query->whereIn('type', ['refund', 'partial_refund']);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for expired transactions
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function($q) {
                $q->where('status', 'pending')
                  ->where('expires_at', '<=', now());
            });
    }

    /**
     * Scope transactions by payment method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope transactions by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // ========== Helper Methods ==========

    /**
     * Check if transaction is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction has failed
     */
    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction has expired
     */
    public function isExpired()
    {
        return $this->status === 'expired' || 
               ($this->status === 'pending' && $this->expires_at && now()->gt($this->expires_at));
    }

    /**
     * Check if transaction is a payment
     */
    public function isPayment()
    {
        return $this->type === 'payment';
    }

    /**
     * Check if transaction is a refund
     */
    public function isRefund()
    {
        return in_array($this->type, ['refund', 'partial_refund']);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'expired' => 'bg-orange-100 text-orange-800',
            'disputed' => 'bg-purple-100 text-purple-800',
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
            'processing' => 'En cours',
            'completed' => 'Complété',
            'failed' => 'Échoué',
            'cancelled' => 'Annulé',
            'expired' => 'Expiré',
            'disputed' => 'Contesté',
            default => 'Inconnu'
        };
    }

    /**
     * Get type label
     */
    public function getTypeLabel()
    {
        return match($this->type) {
            'payment' => 'Paiement',
            'refund' => 'Remboursement complet',
            'partial_refund' => 'Remboursement partiel',
            'chargeback' => 'Contestation',
            'fee' => 'Frais',
            default => 'Inconnu'
        };
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount()
    {
        $prefix = $this->isRefund() ? '-' : '';
        return $prefix . number_format(abs($this->amount), 2) . ' ' . $this->currency;
    }

    /**
     * Get net amount (after fees)
     */
    public function getNetAmount()
    {
        return $this->amount - $this->fee_amount - $this->platform_fee;
    }

    /**
     * Mark as expired
     */
    public function markAsExpired()
    {
        $this->update([
            'status' => 'expired',
            'failed_at' => now(),
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($providerData = [])
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'provider_response' => $providerData,
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed($reason = null, $code = null)
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason,
            'failure_code' => $code,
        ]);
    }
}
