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
        Schema::table('events', function (Blueprint $table) {
            // Payment settings
            $table->boolean('requires_approval')
                ->default(false)
                ->after('price')
                ->comment('Organizer must approve registration before payment');
            
            $table->boolean('allow_installments')
                ->default(false)
                ->after('requires_approval')
                ->comment('Allow payment in installments');
            
            $table->integer('installment_count')
                ->nullable()
                ->after('allow_installments')
                ->comment('Number of installments allowed');
            
            // Cancellation policy
            $table->enum('cancellation_policy', [
                'no_refund',         // No refunds
                'flexible',          // Full refund 24h before event
                'moderate',          // 50% refund up to 7 days before
                'strict',            // 25% refund up to 30 days before
                'custom'             // Custom policy
            ])->default('flexible')->after('installment_count');
            
            $table->text('custom_cancellation_policy')->nullable()
                ->after('cancellation_policy')
                ->comment('Custom cancellation policy text');
            
            // Refund percentages by timeframe (JSON)
            $table->json('refund_schedule')->nullable()
                ->after('custom_cancellation_policy')
                ->comment('{"30_days": 100, "14_days": 75, "7_days": 50, "24_hours": 25}');
            
            // Early bird pricing
            $table->decimal('early_bird_price', 8, 2)->nullable()
                ->after('refund_schedule');
            $table->timestamp('early_bird_deadline')->nullable()
                ->after('early_bird_price');
            
            // Group discount
            $table->boolean('group_discount_enabled')->default(false)
                ->after('early_bird_deadline');
            $table->integer('group_size')->nullable()
                ->after('group_discount_enabled')
                ->comment('Minimum group size for discount');
            $table->decimal('group_price', 8, 2)->nullable()
                ->after('group_size');
            
            // Payment deadline
            $table->integer('payment_deadline_hours')
                ->default(24)
                ->after('group_price')
                ->comment('Hours to complete payment after registration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
