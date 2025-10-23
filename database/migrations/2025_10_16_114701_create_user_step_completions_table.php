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
        Schema::create('user_step_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tutorial_id')->constrained()->onDelete('cascade');
            $table->foreignId('step_id')->constrained('tuto_steps')->onDelete('cascade');
            $table->integer('step_number');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Unique constraint: user can only complete each step once
            $table->unique(['user_id', 'tutorial_id', 'step_id']);
            
            // Index for faster queries
            $table->index(['user_id', 'tutorial_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_step_completions');
    }
};
