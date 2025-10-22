<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixNotificationLinks extends Command
{
    protected $signature = 'notifications:fix-links';
    protected $description = 'Fix notification links from absolute to relative URLs';

    public function handle()
    {
        $this->info('Fixing notification links...');

        // Get all notifications with absolute URLs
        $notifications = DB::table('notifications')
            ->where(function($query) {
                $query->where('link', 'LIKE', 'http://localhost%')
                      ->orWhere('link', 'LIKE', 'http://127.0.0.1%')
                      ->orWhere('link', 'LIKE', 'https://localhost%')
                      ->orWhere('link', 'LIKE', 'https://127.0.0.1%');
            })
            ->get();

        $count = 0;
        foreach ($notifications as $notification) {
            // Remove domain from link
            $newLink = preg_replace('#^https?://[^/]+#', '', $notification->link);
            
            DB::table('notifications')
                ->where('id', $notification->id)
                ->update(['link' => $newLink]);
            
            $count++;
            $this->line("Fixed: {$notification->link} → {$newLink}");
        }

        $this->info("✓ Fixed {$count} notification links!");
        
        return 0;
    }
}
