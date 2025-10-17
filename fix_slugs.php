<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "Checking all tutorials for slug issues...\n\n";

$tutorials = DB::table('tutorials')->get();

foreach ($tutorials as $tutorial) {
    $expectedSlug = Str::slug($tutorial->title, '-', null, ['maxLength' => 255]);
    
    echo "ID: {$tutorial->id}\n";
    echo "Title: {$tutorial->title}\n";
    echo "Current Slug: {$tutorial->slug}\n";
    echo "Expected Slug: {$expectedSlug}\n";
    
    if ($tutorial->slug !== $expectedSlug) {
        echo "⚠️  MISMATCH! ";
        
        // Check if expected slug is available
        $exists = DB::table('tutorials')
            ->where('slug', $expectedSlug)
            ->where('id', '!=', $tutorial->id)
            ->exists();
        
        if ($exists) {
            echo "Expected slug already taken by another tutorial.\n";
        } else {
            echo "Can be updated to: {$expectedSlug}\n";
            // Uncomment to actually update:
            // DB::table('tutorials')->where('id', $tutorial->id)->update(['slug' => $expectedSlug]);
            // echo "✅ Updated!\n";
        }
    } else {
        echo "✅ OK\n";
    }
    
    echo str_repeat("-", 80) . "\n";
}

echo "\nDone!\n";
