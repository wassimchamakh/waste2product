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
        Schema::create('tuto_steps', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key - Relationship with Tutorial
            $table->foreignId('tutorial_id')
                  ->constrained('tutorials')
                  ->onDelete('cascade'); // Delete steps when tutorial is deleted
            
            // Step Information
            $table->integer('step_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            
            // Media for this step
            $table->string('image_url')->nullable();
            $table->string('video_url')->nullable();
            
            // Timing
            $table->integer('estimated_time')->default(0)->comment('Time in minutes');
            
            // Additional Guidance
            $table->text('tips')->nullable();
            $table->text('common_mistakes')->nullable();
            $table->text('required_materials')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Unique constraint: Can't have duplicate step numbers in same tutorial
            $table->unique(['tutorial_id', 'step_number']);
            
            // Index for faster queries
            $table->index('tutorial_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuto_steps');
    }
};