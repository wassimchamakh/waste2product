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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            
            // Media
            $table->string('thumbnail_image')->nullable();
            $table->string('intro_video_url')->nullable();
            
            // Classification
            $table->enum('difficulty_level', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])
                  ->default('Beginner');
            $table->enum('category', [
                'Recycling', 
                'Composting', 
                'Energy', 
                'Water', 
                'Waste Reduction', 
                'Gardening', 
                'DIY', 
                'General'
            ])->default('General');
            $table->integer('estimated_duration')->default(0)->comment('Duration in minutes');
            
            // Metadata
            $table->integer('views_count')->default(0);
            $table->integer('completion_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_ratings')->default(0);
            
            // Prerequisites & Outcomes
            $table->text('prerequisites')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->text('tags')->nullable()->comment('Comma-separated tags');
            
            // Status & Publishing
            $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Draft');
            $table->boolean('is_featured')->default(false);
            
            // Author (assuming you have users table)
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Timestamps
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('difficulty_level');
            $table->index('category');
            $table->index('is_featured');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorials');
    }
};