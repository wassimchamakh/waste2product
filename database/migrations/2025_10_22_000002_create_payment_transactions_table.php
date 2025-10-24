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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Transaction details
            $table->string('transaction_id')->unique()
                ->comment('Our internal transaction ID');
            $table->string('provider_transaction_id')->nullable()
                ->comment('Payment provider transaction ID');
            
            $table->enum('type', [
                'payment',           // Regular payment
                'refund',            // Full refund
                'partial_refund',    // Partial refund
                'chargeback',        // Disputed payment
                'fee'                // Platform or organizer fee
            ]);
            
            $table->enum('status', [
                'pending',           // Payment initiated
                'processing',        // Payment being processed
                'completed',         // Payment successful
                'failed',            // Payment failed
                'cancelled',         // Payment cancelled by user
                'expired',           // Payment session expired
                'disputed'           // Payment disputed
            ])->default('pending');
            
            // Amounts
            $table->decimal('amount', 8, 2);
            $table->decimal('fee_amount', 8, 2)->default(0)
                ->comment('Payment gateway fee');
            $table->decimal('platform_fee', 8, 2)->default(0)
                ->comment('Your platform commission');
            $table->decimal('organizer_amount', 8, 2)
                ->comment('Amount going to event organizer');
            $table->string('currency', 3)->default('TND');
            
            // Payment method
            $table->string('payment_method')
                ->comment('stripe, paypal, flouci, bank_transfer, cash');
            $table->json('payment_method_details')->nullable()
                ->comment('Card last 4 digits, bank name, etc.');
            
            // Provider details
            $table->string('payment_provider')->nullable()
                ->comment('stripe, paypal, flouci');
            $table->string('payment_intent_id')->nullable();
            $table->string('charge_id')->nullable();
            
            // Metadata
            $table->json('provider_response')->nullable()
                ->comment('Full payment provider response');
            $table->text('description')->nullable();
            $table->text('failure_reason')->nullable();
            $table->string('failure_code')->nullable();
            
            // Customer details (snapshot at time of payment)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('billing_address')->nullable();
            
            // IP and fraud prevention
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            
            // Timestamps
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('expires_at')->nullable()
                ->comment('Payment session expiration');
            
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_id');
            $table->index('provider_transaction_id');
            $table->index('status');
            $table->index('payment_method');
            $table->index(['event_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
