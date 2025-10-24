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
        Schema::table('user_step_completions', function (Blueprint $table) {
            // Remove redundant step_number column (data is in tuto_steps table via step_id)
            $table->dropColumn('step_number');
            
            // Add individual index on tutorial_id for faster queries
            $table->index('tutorial_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_step_completions', function (Blueprint $table) {
            // Restore step_number column
            $table->integer('step_number')->after('step_id');
            
            // Remove the index
            $table->dropIndex(['tutorial_id']);
        });
    }
};
