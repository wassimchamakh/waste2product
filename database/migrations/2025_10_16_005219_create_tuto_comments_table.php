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
        Schema::create('tuto_comments', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys - Relationships
            $table->foreignId('tutorial_id')
                  ->constrained('tutorials')
                  ->onDelete('cascade'); // Delete comments when tutorial is deleted
            
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Delete comments when user is deleted
            
            // Self-referential FK for nested replies
            $table->foreignId('parent_comment_id')
                  ->nullable()
                  ->constrained('tuto_comments')
                  ->onDelete('cascade'); // Delete replies when parent is deleted
            
            // Comment Content
            $table->text('comment_text');
            $table->tinyInteger('rating')->nullable()->comment('Rating 1-5 stars');
            
            // Engagement
            $table->integer('helpful_count')->default(0);
            $table->json('is_helpful_by_users')->nullable()->comment('Array of user IDs');
            
            // Moderation
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Flagged'])
                  ->default('Pending');
            $table->text('moderation_note')->nullable();
            $table->foreignId('moderated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
            
            // Edit tracking
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index('tutorial_id');
            $table->index('user_id');
            $table->index('parent_comment_id');
            $table->index('status');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuto_comments');
    }
};