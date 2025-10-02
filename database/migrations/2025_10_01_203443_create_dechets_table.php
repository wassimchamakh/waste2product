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
        Schema::create('dechets', function (Blueprint $table) {
           $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('quantity', 100);
            $table->string('location');
            $table->enum('status', ['available', 'reserved', 'collected'])->default('available');
            $table->string('photo')->nullable();
            $table->string('contact_phone', 15)->nullable();
            $table->text('notes')->nullable();
            $table->integer('views_count')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Foreign Keys
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexes pour performance
            $table->index('status');
            $table->index('is_active');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dechets');
    }
};
