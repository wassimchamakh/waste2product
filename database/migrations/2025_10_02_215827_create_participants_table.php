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
      Schema::create('participants', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->dateTime('registration_date');
            $table->enum('attendance_status', [
                'registered',  // Inscrit
                'confirmed',   // Confirmé
                'attended',    // Présent
                'absent',      // Absent
                'cancelled'    // Annulé
            ])->default('registered');
            
            $table->text('feedback')->nullable();
            $table->tinyInteger('rating')->nullable()->comment('Note de 1 à 5');
            $table->boolean('email_sent')->default(false);
            
            $table->timestamps();
            
            // Index composé pour éviter les doublons
            $table->unique(['event_id', 'user_id']);
            
            // Indexes pour performance
            $table->index('attendance_status');
            $table->index('registration_date');
            $table->index(['event_id', 'attendance_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
