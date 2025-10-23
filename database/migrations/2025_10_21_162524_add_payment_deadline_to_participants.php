<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column doesn't already exist before adding
        if (!Schema::hasColumn('participants', 'payment_deadline')) {
            Schema::table('participants', function (Blueprint $table) {
                $table->timestamp('payment_deadline')->nullable()->after('payment_completed_at');

                // Add index for performance when querying expired payments
                $table->index(['payment_status', 'payment_deadline']);
            });
        }

        // SQLite doesn't support MODIFY COLUMN, so we skip the enum modification
        // The payment_status column already exists from previous migration
        // If you need 'pending_payment' or 'expired' values, they can be used
        // as SQLite's enum is just a text constraint check
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
    }
};
