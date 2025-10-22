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
            // Add payment deadline column
            $table->timestamp('payment_deadline')->nullable()->after('payment_completed_at');
            
            // Add index for performance when querying expired payments
            $table->index(['payment_status', 'payment_deadline']);
        });
        
        // Also update payment_status enum to include pending_payment and expired
        DB::statement("ALTER TABLE participants MODIFY COLUMN payment_status ENUM('not_required', 'pending_payment', 'completed', 'failed', 'refunded', 'partially_refunded', 'expired') DEFAULT 'not_required'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'payment_deadline']);
            $table->dropColumn('payment_deadline');
        });
        
        // Revert payment_status enum
        DB::statement("ALTER TABLE participants MODIFY COLUMN payment_status ENUM('not_required', 'pending', 'completed', 'failed', 'refunded', 'partially_refunded') DEFAULT 'not_required'");
    }
};
