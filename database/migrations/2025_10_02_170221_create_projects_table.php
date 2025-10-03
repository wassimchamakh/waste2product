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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('difficulty_level', ['facile','intermédiaire','difficile']);
            $table->string('estimated_time');
            $table->integer('impact_score')->default(0);
            $table->string('photo')->nullable();
            $table->enum('status', ['draft','published','archived'])->default('draft');
            $table->integer('views_count')->default(0); // Ajouté
            $table->integer('favorites_count')->default(0); // Ajouté
            $table->decimal('average_rating', 3, 2)->default(0); // Ajouté
            $table->integer('reviews_count')->default(0); // Ajouté
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};