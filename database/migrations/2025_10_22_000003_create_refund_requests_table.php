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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('original_transaction_id')
                ->constrained('payment_transactions')
                ->onDelete('cascade');
            
            // Refund details
            $table->decimal('refund_amount', 8, 2);
            $table->enum('refund_type', [
                'full',              // 100% refund
                'partial',           // Partial refund
                'policy_based'       // Based on cancellation policy
            ]);
            
            $table->enum('status', [
                'pending',           // Request submitted
                'approved',          // Approved by organizer/admin
                'rejected',          // Rejected
                'processing',        // Refund being processed
                'completed',         // Refund completed
                'failed'             // Refund failed
            ])->default('pending');
            
            // Request details
            $table->text('reason')
                ->comment('User reason for refund');
            $table->enum('reason_category', [
                'event_cancelled',
                'event_rescheduled',
                'personal_emergency',
                'cannot_attend',
                'dissatisfied',
                'other'
            ])->nullable();
            
            // Response
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Refund transaction
            $table->foreignId('refund_transaction_id')->nullable()
                ->constrained('payment_transactions')
                ->onDelete('set null');
            
            // Approved/rejected by
            $table->foreignId('processed_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index(['event_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
