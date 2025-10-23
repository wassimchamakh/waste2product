<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\RefundRequest;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Refund;

class RefundController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show refund request form
     */
    public function showRefundForm($event, $participant)
    {
        $participant = Participant::with('event')->findOrFail($participant);

        // Verify ownership
        if ($participant->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if refund can be requested
        if (!$participant->canRequestRefund()) {
            return redirect()->route('Events.show', $event)
                ->with('error', 'Vous ne pouvez pas demander de remboursement pour cet événement.');
        }

        $availableRefund = $participant->getAvailableRefundAmount();
        $eligibleRefundAmount = $availableRefund;

        if ($eligibleRefundAmount <= 0) {
            return redirect()->route('Events.show', $event)
                ->with('error', 'Aucun remboursement disponible selon la politique d\'annulation.');
        }

        // Calculate refund percentage based on cancellation policy
        $refundPercentage = 100;
        if ($participant->amount_paid > 0) {
            $refundPercentage = round(($eligibleRefundAmount / $participant->amount_paid) * 100);
        }
        
        // Calculate days until event
        $eventDate = \Carbon\Carbon::parse($participant->event->date_start);
        $now = \Carbon\Carbon::now();
        $daysUntilEvent = $now->diffInDays($eventDate, false);
        if ($daysUntilEvent < 0) {
            $daysUntilEvent = 0;
        }

        return view('frontoffice.events.refund-request', [
            'event' => $participant->event,
            'participant' => $participant,
            'availableRefund' => $availableRefund,
            'eligibleRefundAmount' => $eligibleRefundAmount,
            'refundPercentage' => $refundPercentage,
            'daysUntilEvent' => $daysUntilEvent,
        ]);
    }

    /**
     * Submit refund request
     */
    public function requestRefund(Request $request, $event, $participant)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
            'reason_category' => 'required|in:event_cancelled,event_rescheduled,personal_emergency,cannot_attend,dissatisfied,other'
        ]);

        $participant = Participant::with('event')->findOrFail($participant);

        // Verify ownership
        if ($participant->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Non autorisé');
        }

        // Check eligibility
        if (!$participant->canRequestRefund()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas demander de remboursement');
        }

        // Calculate refund amount
        $refundAmount = $participant->getAvailableRefundAmount();

        if ($refundAmount <= 0) {
            return redirect()->back()
                ->with('error', 'Aucun remboursement disponible');
        }

        // Get original transaction
        $originalTransaction = PaymentTransaction::where('participant_id', $participant->id)
            ->where('type', 'payment')
            ->where('status', 'completed')
            ->first();

        if (!$originalTransaction) {
            return redirect()->back()
                ->with('error', 'Transaction de paiement introuvable');
        }

        // Check if refund request already exists
        $existingRequest = RefundRequest::where('participant_id', $participant->id)
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()
                ->with('error', 'Une demande de remboursement est déjà en cours');
        }

        // Create refund request
        $refundRequest = RefundRequest::create([
            'participant_id' => $participant->id,
            'event_id' => $participant->event_id,
            'user_id' => Auth::id(),
            'original_transaction_id' => $originalTransaction->id,
            'refund_amount' => $refundAmount,
            'refund_type' => $refundAmount >= $participant->amount_paid ? 'full' : 'policy_based',
            'reason' => $request->reason,
            'reason_category' => $request->reason_category,
            'status' => 'pending',
        ]);

        // Update participant
        $participant->update([
            'refund_requested_at' => now(),
            'refund_reason' => $request->reason,
        ]);

        // TODO: Notify organizer
        // dispatch(new NotifyOrganizerRefundRequest($refundRequest));

        return redirect()->route('Events.show', $event)
            ->with('success', 'Votre demande de remboursement a été soumise avec succès. L\'organisateur examinera votre demande sous peu.');
    }

    /**
     * Approve refund (Organizer/Admin)
     */
    public function approveRefund(Request $request, $event, $refundRequest)
    {
        $refundRequest = RefundRequest::with(['participant', 'event', 'originalTransaction'])
            ->findOrFail($refundRequest);

        $event = $refundRequest->event;

        // Check authorization (admin or event organizer)
        if (!Auth::user()->is_admin && $event->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Non autorisé');
        }

        // Check if already processed
        if (!$refundRequest->isPending()) {
            return redirect()->back()
                ->with('error', 'Cette demande a déjà été traitée');
        }

        try {
            // Mark as processing
            $refundRequest->markAsProcessing();

            // Process refund via Stripe
            $refund = Refund::create([
                'payment_intent' => $refundRequest->originalTransaction->payment_intent_id,
                'amount' => round($refundRequest->refund_amount * 100), // Convert to cents
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'refund_request_id' => $refundRequest->id,
                    'participant_id' => $refundRequest->participant_id,
                    'event_id' => $refundRequest->event_id,
                ],
            ]);

            // Create refund transaction
            $refundTransaction = PaymentTransaction::create([
                'participant_id' => $refundRequest->participant_id,
                'event_id' => $refundRequest->event_id,
                'user_id' => $refundRequest->user_id,
                'transaction_id' => 'REFUND-' . strtoupper(Str::random(12)),
                'provider_transaction_id' => $refund->id,
                'type' => $refundRequest->refund_type === 'full' ? 'refund' : 'partial_refund',
                'status' => 'completed',
                'amount' => -$refundRequest->refund_amount, // Negative for refund
                'fee_amount' => 0,
                'platform_fee' => 0,
                'organizer_amount' => -$refundRequest->refund_amount,
                'currency' => 'TND',
                'payment_method' => 'stripe',
                'payment_provider' => 'stripe',
                'customer_name' => $refundRequest->participant->user->name,
                'customer_email' => $refundRequest->participant->user->email,
                'provider_response' => json_decode(json_encode($refund), true),
                'completed_at' => now(),
            ]);

            // Update refund request
            $refundRequest->update([
                'status' => 'completed',
                'refund_transaction_id' => $refundTransaction->id,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            // Update participant
            $participant = $refundRequest->participant;
            $participant->update([
                'payment_status' => $refundRequest->refund_type === 'full' ? 'refunded' : 'partially_refunded',
                'amount_refunded' => $refundRequest->refund_amount,
                'attendance_status' => 'cancelled',
                'refund_completed_at' => now(),
                'refund_transaction_id' => $refund->id,
            ]);

            // TODO: Send confirmation email
            // dispatch(new SendRefundConfirmation($participant));

            Log::info("Refund approved and processed: {$refundRequest->id}");

            return redirect()->back()
                ->with('success', 'Remboursement effectué avec succès pour un montant de ' . number_format($refundRequest->refund_amount, 3) . ' ' . config('payments.currency'));

        } catch (\Exception $e) {
            Log::error('Refund processing error: ' . $e->getMessage());
            
            $refundRequest->update([
                'status' => 'failed',
                'admin_notes' => 'Erreur: ' . $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement: ' . $e->getMessage());
        }
    }

    /**
     * Reject refund (Organizer/Admin)
     */
    public function rejectRefund(Request $request, $event, $refundRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $refundRequest = RefundRequest::with('event')->findOrFail($refundRequest);

        // Check authorization
        if (!Auth::user()->is_admin && $refundRequest->event->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Non autorisé');
        }

        if (!$refundRequest->isPending()) {
            return redirect()->back()
                ->with('error', 'Cette demande a déjà été traitée');
        }

        $refundRequest->reject(Auth::id(), $request->rejection_reason);

        // TODO: Send rejection email
        // dispatch(new SendRefundRejection($refundRequest));

        return redirect()->back()
            ->with('success', 'Demande de remboursement rejetée');
    }

    /**
     * List refund requests (Organizer/Admin)
     */
    public function listRefundRequests(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // Check authorization
        if (!Auth::user()->is_admin && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $query = RefundRequest::with(['participant.user', 'event'])
            ->where('event_id', $eventId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $refundRequests = $query->latest()->paginate(20);

        // Get counts for filter tabs
        $allCount = RefundRequest::where('event_id', $eventId)->count();
        $pendingCount = RefundRequest::where('event_id', $eventId)->where('status', 'pending')->count();
        $completedCount = RefundRequest::where('event_id', $eventId)->where('status', 'completed')->count();
        $rejectedCount = RefundRequest::where('event_id', $eventId)->where('status', 'rejected')->count();

        return view('frontoffice.events.refund-requests', [
            'event' => $event,
            'refundRequests' => $refundRequests,
            'allCount' => $allCount,
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }
}
