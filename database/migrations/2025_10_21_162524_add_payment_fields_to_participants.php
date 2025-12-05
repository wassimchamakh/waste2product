<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Payment tracking
            $table->enum('payment_status', [
                'not_required',  // Free event
                'pending',       // Payment initiated but not completed
                'completed',     // Payment successful
                'failed',        // Payment failed
                'refunded',      // Payment refunded
                'partially_refunded' // Partial refund
            ])->default('not_required')->after('attendance_status');
            
            $table->decimal('amount_paid', 8, 2)->nullable()->after('payment_status');
            $table->decimal('amount_refunded', 8, 2)->default(0)->after('amount_paid');
            
            // Payment provider details
            $table->string('payment_method')->nullable()->after('amount_refunded')
                ->comment('stripe, paypal, flouci, bank_transfer');
            $table->string('payment_provider_id')->nullable()->after('payment_method')
                ->comment('Transaction ID from payment provider');
            $table->string('payment_intent_id')->nullable()->after('payment_provider_id')
                ->comment('Stripe Payment Intent ID or equivalent');
            
            // Payment timestamps
            $table->timestamp('payment_completed_at')->nullable()->after('payment_intent_id');
            $table->timestamp('refund_requested_at')->nullable()->after('payment_completed_at');
            $table->timestamp('refund_completed_at')->nullable()->after('refund_requested_at');
            
            // Refund details
            $table->text('refund_reason')->nullable()->after('refund_completed_at');
            $table->string('refund_transaction_id')->nullable()->after('refund_reason');
            
            // Invoice
            $table->string('invoice_number')->nullable()->unique()->after('refund_transaction_id');
            $table->timestamp('invoice_sent_at')->nullable()->after('invoice_number');
            
            // Index for performance
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index(['event_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'payment_status']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['payment_status']);
            
            $table->dropColumn([
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
            ]);
        });
    }
};
