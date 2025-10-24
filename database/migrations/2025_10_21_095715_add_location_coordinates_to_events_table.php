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
            // Google Maps link (shareable URL)
            $table->string('maps_link')->nullable()->after('location');
            
            // GPS coordinates for map display
            $table->decimal('latitude', 10, 7)->nullable()->after('maps_link');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['maps_link', 'latitude', 'longitude']);
        });
    }
};
