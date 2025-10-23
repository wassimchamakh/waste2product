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
        Schema::table('participants', function (Blueprint $table) {
            // Add sentiment analysis columns
            $table->string('sentiment_label', 20)->nullable()->after('feedback')
                ->comment('Sentiment: positive, negative, neutral');
            
            $table->decimal('sentiment_score', 5, 4)->nullable()->after('sentiment_label')
                ->comment('Sentiment score from -1.0 to 1.0');
            
            $table->decimal('sentiment_confidence', 5, 4)->nullable()->after('sentiment_score')
                ->comment('Confidence level 0.0 to 1.0');
            
            $table->json('sentiment_details')->nullable()->after('sentiment_confidence')
                ->comment('Detailed sentiment analysis data');
            
            $table->json('feedback_themes')->nullable()->after('sentiment_details')
                ->comment('Extracted themes from feedback');
            
            $table->timestamp('sentiment_analyzed_at')->nullable()->after('feedback_themes')
                ->comment('When sentiment analysis was performed');
            
            // Add index for sentiment queries
            $table->index('sentiment_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropIndex(['sentiment_label']);
            $table->dropColumn([
                'sentiment_label',
                'sentiment_score',
                'sentiment_confidence',
                'sentiment_details',
                'feedback_themes',
                'sentiment_analyzed_at'
            ]);
        });
    }
};
