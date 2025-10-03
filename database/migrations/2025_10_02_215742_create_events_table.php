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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['workshop', 'collection', 'training', 'repair_cafe']);
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->string('location');
            $table->integer('max_participants')->default(20);
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('published');
            $table->string('image')->nullable();
            
            // Foreign Key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexes pour performance
            $table->index('type');
            $table->index('status');
            $table->index('date_start');
            $table->index('date_end');
            $table->index(['date_start', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
