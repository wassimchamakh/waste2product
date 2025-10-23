<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show payment page
     */
    public function showPaymentPage($event, $participant)
    {
        $event = Event::findOrFail($event);
        $participant = Participant::with('user')->findOrFail($participant);

        // Verify ownership
        if ($participant->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        // Check if payment already completed
        if ($participant->payment_status === 'completed') {
            return redirect()->route('Events.show', $event->id)
                ->with('info', 'Votre paiement a déjà été effectué.');
        }

        // Get active transaction
        $transaction = PaymentTransaction::where('participant_id', $participant->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$transaction) {
            // Create new transaction if none exists
            $transaction = $this->createPaymentTransaction($participant, $event);
        }

        // Check if expired
        if ($transaction->isExpired()) {
            $transaction->markAsExpired();
            $participant->update([
                'payment_status' => 'failed',
                'attendance_status' => 'cancelled'
            ]);

            return redirect()->route('Events.show', $event->id)
                ->with('error', 'Le délai de paiement a expiré. Veuillez vous réinscrire.');
        }

        try {
            // Create or retrieve Stripe Payment Intent
            if ($transaction->payment_intent_id) {
                $paymentIntent = PaymentIntent::retrieve($transaction->payment_intent_id);
            } else {
                $paymentIntent = PaymentIntent::create([
                    'amount' => round($transaction->amount * 100), // Convert to cents
                    'currency' => strtolower($transaction->currency),
                    'metadata' => [
                        'transaction_id' => $transaction->transaction_id,
                        'event_id' => $event->id,
                        'participant_id' => $participant->id,
                        'user_id' => Auth::id(),
                        'event_title' => $event->title,
                    ],
                    'description' => "Inscription: {$event->title}",
                    'receipt_email' => Auth::user()->email,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                ]);

                // Update transaction with payment intent ID
                $transaction->update([
                    'payment_intent_id' => $paymentIntent->id,
                    'status' => 'processing'
                ]);
            }

            return view('frontoffice.events.payment', [
                'event' => $event,
                'participant' => $participant,
                'transaction' => $transaction,
                'amount' => $transaction->amount,
                'paymentDeadline' => $transaction->expires_at,
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => config('services.stripe.key'),
            ]);

        } catch (\Exception $e) {
            Log::error('Payment page error: ' . $e->getMessage());
            
            return redirect()->route('Events.show', $event->id)
                ->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');
        }
    }

    /**
     * Create payment transaction
     */
    private function createPaymentTransaction(Participant $participant, Event $event)
    {
        $amount = $event->getCurrentPrice();
        $transactionId = 'TXN-' . strtoupper(Str::random(12));

        $platformFeePercent = config('payments.platform_fee_percent', 5);
        $platformFee = $amount * ($platformFeePercent / 100);
        $organizerAmount = $amount - $platformFee;

        return PaymentTransaction::create([
            'participant_id' => $participant->id,
            'event_id' => $event->id,
            'user_id' => $participant->user_id,
            'transaction_id' => $transactionId,
            'type' => 'payment',
            'status' => 'pending',
            'amount' => $amount,
            'fee_amount' => 0, // Will be updated after payment
            'platform_fee' => $platformFee,
            'organizer_amount' => $organizerAmount,
            'currency' => 'EUR', // Changed from TND to EUR for Stripe test mode
            'payment_method' => 'stripe',
            'customer_name' => $participant->user->name,
            'customer_email' => $participant->user->email,
            'customer_phone' => $participant->user->phone ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'initiated_at' => now(),
            'expires_at' => $event->getPaymentDeadline(),
        ]);
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature if secret is configured
            if ($webhookSecret) {
                $event = Webhook::constructEvent(
                    $payload,
                    $sigHeader,
                    $webhookSecret
                );
            } else {
                // For testing without webhook secret
                $event = json_decode($payload, false);
            }
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        try {
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSuccess($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailure($event->data->object);
                    break;

                case 'charge.refunded':
                    $this->handleRefund($event->data->object);
                    break;

                default:
                    Log::info('Unhandled webhook event type: ' . $event->type);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook handling error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handling failed'], 500);
        }
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentSuccess($paymentIntent)
    {
        $transaction = PaymentTransaction::where('payment_intent_id', $paymentIntent->id)->first();

        if (!$transaction) {
            Log::error("Transaction not found for payment intent: {$paymentIntent->id}");
            return;
        }

        // Get charge details
        $charge = $paymentIntent->charges->data[0] ?? null;
        $feeAmount = $charge ? ($charge->balance_transaction->fee ?? 0) / 100 : 0;

        // Update transaction
        $transaction->update([
            'status' => 'completed',
            'provider_transaction_id' => $paymentIntent->id,
            'charge_id' => $charge->id ?? null,
            'payment_provider' => 'stripe',
            'fee_amount' => $feeAmount,
            'payment_method_details' => [
                'type' => $charge->payment_method_details->type ?? 'card',
                'brand' => $charge->payment_method_details->card->brand ?? null,
                'last4' => $charge->payment_method_details->card->last4 ?? null,
            ],
            'provider_response' => json_decode(json_encode($paymentIntent), true),
            'completed_at' => now(),
        ]);

        // Update participant
        $participant = Participant::find($transaction->participant_id);
        if ($participant) {
            $participant->update([
                'attendance_status' => 'confirmed',
                'payment_status' => 'completed',
                'amount_paid' => $transaction->amount,
                'payment_method' => 'stripe',
                'payment_provider_id' => $paymentIntent->id,
                'payment_intent_id' => $paymentIntent->id,
                'payment_completed_at' => now(),
                'invoice_number' => $this->generateInvoiceNumber(),
            ]);

            // Send ticket email after payment completion
            try {
                \Illuminate\Support\Facades\Mail::to($participant->user->email)->send(
                    new \App\Mail\TicketNotification($participant)
                );
                Log::info("Ticket sent to participant {$participant->id} after payment");
            } catch (\Exception $e) {
                Log::error("Failed to send ticket email: " . $e->getMessage());
            }
            
            Log::info("Payment completed for participant {$participant->id}");
        }
    }

    /**
     * Handle payment failure
     */
    private function handlePaymentFailure($paymentIntent)
    {
        $transaction = PaymentTransaction::where('payment_intent_id', $paymentIntent->id)->first();

        if (!$transaction) {
            return;
        }

        $errorMessage = $paymentIntent->last_payment_error->message ?? 'Unknown error';
        $errorCode = $paymentIntent->last_payment_error->code ?? null;

        $transaction->update([
            'status' => 'failed',
            'failure_reason' => $errorMessage,
            'failure_code' => $errorCode,
            'provider_response' => json_decode(json_encode($paymentIntent), true),
            'failed_at' => now(),
        ]);

        $participant = Participant::find($transaction->participant_id);
        if ($participant) {
            $participant->update([
                'payment_status' => 'failed',
            ]);

            // TODO: Send failure notification
            // dispatch(new SendPaymentFailure($participant));
            
            Log::warning("Payment failed for participant {$participant->id}: {$errorMessage}");
        }
    }

    /**
     * Handle refund
     */
    private function handleRefund($charge)
    {
        // Find the original transaction
        $transaction = PaymentTransaction::where('charge_id', $charge->id)->first();

        if (!$transaction) {
            return;
        }

        $refundAmount = $charge->amount_refunded / 100;

        // Update participant
        $participant = Participant::find($transaction->participant_id);
        if ($participant) {
            $isFullRefund = $refundAmount >= $participant->amount_paid;
            
            $participant->update([
                'payment_status' => $isFullRefund ? 'refunded' : 'partially_refunded',
                'amount_refunded' => $refundAmount,
                'refund_completed_at' => now(),
            ]);

            Log::info("Refund processed for participant {$participant->id}: {$refundAmount} TND");
        }
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = config('payments.settings.invoice_prefix', 'INV');
        $year = date('Y');
        
        $count = Participant::whereNotNull('invoice_number')
            ->whereYear('created_at', $year)
            ->count() + 1;

        return $prefix . '-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Payment success page
     */
    public function success($event, $participant, Request $request)
    {
        $participant = Participant::with('event', 'user')->findOrFail($participant);

        if ($participant->user_id !== Auth::id()) {
            abort(403);
        }

        // If called with confirm parameter, update payment status
        if ($request->has('confirm') && $request->has('payment_intent')) {
            $paymentIntentId = $request->input('payment_intent');
            
            try {
                // Retrieve payment intent from Stripe
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
                
                if ($paymentIntent->status === 'succeeded' && $participant->payment_status !== 'completed') {
                    // Update participant
                    $participant->update([
                        'payment_status' => 'completed',
                        'attendance_status' => 'confirmed',
                        'payment_method' => 'stripe',
                        'payment_intent_id' => $paymentIntentId,
                        'payment_completed_at' => now(),
                        'amount_paid' => $paymentIntent->amount / 100, // Convert from cents
                        'invoice_number' => 'INV-' . strtoupper(\Illuminate\Support\Str::random(10)),
                    ]);
                    
                    // Update transaction
                    $transaction = PaymentTransaction::where('participant_id', $participant->id)
                        ->where('payment_intent_id', $paymentIntentId)
                        ->first();
                    
                    if ($transaction) {
                        $transaction->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                            'fee_amount' => ($paymentIntent->charges->data[0]->balance_transaction ?? 0) ? 
                                (\Stripe\BalanceTransaction::retrieve($paymentIntent->charges->data[0]->balance_transaction)->fee / 100) : 0,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Payment confirmation error: ' . $e->getMessage());
            }
            
            // Return JSON response for AJAX call
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
        }

        if ($participant->payment_status !== 'completed') {
            return redirect()->route('Events.show', $event)
                ->with('warning', 'Le paiement n\'est pas encore confirmé.');
        }

        return view('frontoffice.events.payment-success', [
            'event' => $participant->event,
            'participant' => $participant,
        ]);
    }

    /**
     * Payment failure page
     */
    public function failure($event, $participant)
    {
        $participant = Participant::with('event')->findOrFail($participant);

        if ($participant->user_id !== Auth::id()) {
            abort(403);
        }

        return view('frontoffice.events.payment-failure', [
            'event' => $participant->event,
            'participant' => $participant,
        ]);
    }

    /**
     * Cancel payment
     */
    public function cancel($eventId, $participantId)
    {
        $participant = Participant::findOrFail($participantId);

        if ($participant->user_id !== Auth::id()) {
            abort(403);
        }

        // Update participant status
        $participant->update([
            'payment_status' => 'failed',
            'attendance_status' => 'cancelled',
        ]);

        // Update transaction
        PaymentTransaction::where('participant_id', $participantId)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        return redirect()->route('Events.show', $eventId)
            ->with('info', 'Paiement annulé.');
    }
}
