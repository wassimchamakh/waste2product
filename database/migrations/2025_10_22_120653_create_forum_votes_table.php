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
        Schema::create('forum_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('votable'); // votable_id and votable_type for polymorphic relation
            $table->tinyInteger('vote')->comment('-1 for downvote, 1 for upvote');
            $table->timestamps();
            
            // Ensure one vote per user per votable item
            $table->unique(['user_id', 'votable_id', 'votable_type']);
            $table->index(['votable_id', 'votable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_votes');
    }
};
