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
        Schema::create('project_steps', function (Blueprint $table) {
           $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->string('title');
            $table->text('description');
            $table->text('materials_needed')->nullable();
            $table->text('tools_required')->nullable();
            $table->string('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_steps');
    }
};
