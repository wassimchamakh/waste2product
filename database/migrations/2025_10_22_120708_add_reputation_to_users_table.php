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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('reputation')->default(0)->after('email');
            $table->string('badge')->nullable()->after('reputation'); // bronze, silver, gold, platinum
            $table->integer('posts_count')->default(0)->after('badge');
            $table->integer('comments_count')->default(0)->after('posts_count');
            $table->integer('best_answers_count')->default(0)->after('comments_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reputation', 'badge', 'posts_count', 'comments_count', 'best_answers_count']);
        });
    }
};
