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
        Schema::create('dechet_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dechet_id')->constrained('dechets')->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate favorites
            $table->unique(['user_id', 'dechet_id']);

            // Indexes for performance
            $table->index('user_id');
            $table->index('dechet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dechet_favorites');
    }
};
