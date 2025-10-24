<?php

namespace App\Console\Commands;

use App\Models\Participant;
use App\Models\PaymentTransaction;
use App\Models\Event;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpirePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:expire
                            {--dry-run : Run without making changes}
                            {--hours= : Custom expiration hours (overrides event settings)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending payments that have exceeded their deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $customHours = $this->option('hours');
        
        $this->info('🔍 Checking for expired payments...');
        $this->newLine();
        
        // Find pending payments
        $expiredCount = 0;
        $reminderCount = 0;
        
        // Get all participants with pending payment status
        $pendingParticipants = Participant::where('payment_status', 'pending_payment')
            ->whereNotNull('payment_deadline')
            ->with(['event', 'user'])
            ->get();
        
        $this->info("Found {$pendingParticipants->count()} participants with pending payments");
        $this->newLine();
        
        foreach ($pendingParticipants as $participant) {
            $event = $participant->event;
            
            // Skip if event has no payment requirement
            if (!$event || $event->isFree()) {
                continue;
            }
            
            $deadline = Carbon::parse($participant->payment_deadline);
            $now = Carbon::now();
            
            // Check if payment has expired
            if ($now->greaterThan($deadline)) {
                // Payment expired
                $this->warn("⏰ EXPIRED: {$participant->name} - Event: {$event->title}");
                $this->line("   Deadline was: {$deadline->format('Y-m-d H:i:s')}");
                
                if (!$isDryRun) {
                    // Update participant status
                    $participant->update([
                        'payment_status' => 'expired',
                        'status' => 'cancelled'
                    ]);
                    
                    // Find and expire related transaction
                    $transaction = PaymentTransaction::where('participant_id', $participant->id)
                        ->where('status', 'pending')
                        ->first();
                    
                    if ($transaction) {
                        $transaction->markAsExpired();
                    }
                    
                    // TODO: Send expiration notification email
                    // Mail::to($participant->user)->send(new PaymentExpired($participant, $event));
                    
                    $this->line("   ✓ Status updated to 'expired'");
                    $expiredCount++;
                } else {
                    $this->line("   [DRY RUN] Would expire this payment");
                }
                
                $this->newLine();
            } 
            // Check if reminder should be sent (24 hours before deadline)
            else if ($now->diffInHours($deadline, false) <= 24 && $now->diffInHours($deadline, false) > 0) {
                $hoursLeft = round($now->diffInHours($deadline, false));
                
                $this->comment("⚠️  REMINDER: {$participant->name} - {$hoursLeft}h left");
                $this->line("   Event: {$event->title}");
                $this->line("   Deadline: {$deadline->format('Y-m-d H:i:s')}");
                
                if (!$isDryRun) {
                    // Check if reminder already sent today
                    $lastReminderKey = "payment_reminder_{$participant->id}";
                    $lastReminder = cache($lastReminderKey);
                    
                    if (!$lastReminder || Carbon::parse($lastReminder)->isYesterday()) {
                        // TODO: Send reminder email
                        // Mail::to($participant->user)->send(new PaymentReminder($participant, $event, $hoursLeft));
                        
                        // Cache that reminder was sent
                        cache([$lastReminderKey => now()], now()->addDay());
                        
                        $this->line("   ✓ Reminder sent");
                        $reminderCount++;
                    } else {
                        $this->line("   ℹ Reminder already sent today");
                    }
                } else {
                    $this->line("   [DRY RUN] Would send reminder");
                }
                
                $this->newLine();
            }
        }
        
        // Summary
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('                SUMMARY                ');
        $this->info('═══════════════════════════════════════');
        
        if ($isDryRun) {
            $this->warn('⚡ DRY RUN MODE - No changes were made');
        }
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Pending Payments', $pendingParticipants->count()],
                ['Expired Payments', $expiredCount],
                ['Reminders Sent', $reminderCount],
            ]
        );
        
        if ($expiredCount > 0) {
            $this->warn("⏰ {$expiredCount} payment(s) expired");
        }
        
        if ($reminderCount > 0) {
            $this->comment("📧 {$reminderCount} reminder(s) sent");
        }
        
        if ($expiredCount == 0 && $reminderCount == 0) {
            $this->info('✅ No actions needed - all payments are up to date');
        }
        
        $this->newLine();
        
        return Command::SUCCESS;
    }
    
    /**
     * Calculate payment deadline based on event settings
     */
    protected function calculateDeadline(Event $event, Carbon $registrationDate): Carbon
    {
        $hours = $this->option('hours') ?? $event->payment_deadline_hours ?? 48;
        return $registrationDate->copy()->addHours($hours);
    }
}
