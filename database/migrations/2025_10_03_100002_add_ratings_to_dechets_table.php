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
        Schema::table('dechets', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0)->after('views_count');
            $table->integer('reviews_count')->default(0)->after('average_rating');
            $table->integer('favorites_count')->default(0)->after('reviews_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dechets', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'reviews_count', 'favorites_count']);
        });
    }
};
