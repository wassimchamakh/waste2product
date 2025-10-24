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
        Schema::table('events', function (Blueprint $table) {
            // Program schedule as JSON (array of time slots)
            $table->json('program')->nullable()->after('description');
            
            // Learning objectives (what participants will learn/do)
            $table->text('learning_objectives')->nullable()->after('program');
            
            // Required materials
            $table->text('required_materials')->nullable()->after('learning_objectives');
            
            // Skill level required
            $table->string('skill_level')->default('beginner')->after('required_materials'); // beginner, intermediate, advanced, all
            
            // Location details
            $table->text('access_instructions')->nullable()->after('location');
            $table->boolean('parking_available')->default(false)->after('access_instructions');
            $table->boolean('accessible_pmr')->default(false)->after('parking_available'); // PMR = Personnes à Mobilité Réduite
            $table->boolean('wifi_available')->default(false)->after('accessible_pmr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'program',
                'learning_objectives',
                'required_materials',
                'skill_level',
                'access_instructions',
                'parking_available',
                'accessible_pmr',
                'wifi_available'
            ]);
        });
    }
};
