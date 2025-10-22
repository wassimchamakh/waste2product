@extends('FrontOffice.layout1.app')

@section('title', 'Demandes de Remboursement - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('Events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à l'événement
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Demandes de Remboursement</h1>
            <p class="text-gray-600 mt-2">{{ $event->title }}</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-red-800">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('Events.refund.list', $event->id) }}" 
                       class="px-6 py-4 text-sm font-medium {{ !request('status') ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                        Toutes ({{ $allCount }})
                    </a>
                    <a href="{{ route('Events.refund.list', $event->id) }}?status=pending" 
                       class="px-6 py-4 text-sm font-medium {{ request('status') == 'pending' ? 'border-b-2 border-yellow-500 text-yellow-600' : 'text-gray-600 hover:text-gray-800' }}">
                        En Attente ({{ $pendingCount }})
                    </a>
                    <a href="{{ route('Events.refund.list', $event->id) }}?status=completed" 
                       class="px-6 py-4 text-sm font-medium {{ request('status') == 'completed' ? 'border-b-2 border-green-500 text-green-600' : 'text-gray-600 hover:text-gray-800' }}">
                        Approuvées ({{ $completedCount }})
                    </a>
                    <a href="{{ route('Events.refund.list', $event->id) }}?status=rejected" 
                       class="px-6 py-4 text-sm font-medium {{ request('status') == 'rejected' ? 'border-b-2 border-red-500 text-red-600' : 'text-gray-600 hover:text-gray-800' }}">
                        Rejetées ({{ $rejectedCount }})
                    </a>
                </nav>
            </div>
        </div>

        @if($refundRequests->count() > 0)
        <!-- Refund Requests List -->
        <div class="space-y-4">
            @foreach($refundRequests as $refundRequest)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $refundRequest->participant->user->name }}
                                </h3>
                                @if($refundRequest->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-clock mr-1"></i>En Attente
                                </span>
                                @elseif($refundRequest->status == 'processing')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-spinner mr-1"></i>En Traitement
                                </span>
                                @elseif($refundRequest->status == 'completed')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>Approuvée
                                </span>
                                @elseif($refundRequest->status == 'rejected')
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i>Rejetée
                                </span>
                                @elseif($refundRequest->status == 'failed')
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Échec
                                </span>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                <div class="text-gray-600">
                                    <i class="fas fa-envelope w-4 mr-1"></i>
                                    {{ $refundRequest->participant->user->email }}
                                </div>
                                <div class="text-gray-600">
                                    <i class="fas fa-calendar w-4 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($refundRequest->created_at)->format('d/m/Y à H:i') }}
                                </div>
                                <div class="text-gray-600">
                                    <i class="fas fa-money-bill-wave w-4 mr-1"></i>
                                    {{ number_format($refundRequest->refund_amount, 3) }} {{ config('payments.currency') }}
                                </div>
                                <div class="text-gray-600">
                                    <i class="fas fa-tag w-4 mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $refundRequest->reason_category)) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Refund Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Détails de la Demande</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <div>
                                <span class="text-sm text-gray-600">Type:</span>
                                <span class="text-sm font-medium text-gray-900 ml-2">
                                    {{ $refundRequest->refund_type == 'full' ? 'Remboursement Intégral' : 'Remboursement Partiel' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Montant Payé:</span>
                                <span class="text-sm font-medium text-gray-900 ml-2">
                                    {{ number_format($refundRequest->participant->amount_paid, 3) }} {{ config('payments.currency') }}
                                </span>
                            </div>
                            @if($refundRequest->participant->invoice_number)
                            <div>
                                <span class="text-sm text-gray-600">Facture:</span>
                                <span class="text-sm font-mono text-gray-900 ml-2">
                                    {{ $refundRequest->participant->invoice_number }}
                                </span>
                            </div>
                            @endif
                            <div>
                                <span class="text-sm text-gray-600">Transaction ID:</span>
                                <span class="text-sm font-mono text-gray-900 ml-2">
                                    {{ $refundRequest->originalTransaction->transaction_id ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Raison:</p>
                            <p class="text-sm text-gray-800">{{ $refundRequest->reason }}</p>
                        </div>

                        @if($refundRequest->admin_notes)
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Notes de l'Administrateur:</p>
                            <p class="text-sm text-gray-800">{{ $refundRequest->admin_notes }}</p>
                        </div>
                        @endif

                        @if($refundRequest->rejection_reason)
                        <div class="border-t border-gray-200 pt-3 mt-3 bg-red-50 -m-4 p-4 rounded">
                            <p class="text-sm font-semibold text-red-700 mb-1">Raison du Rejet:</p>
                            <p class="text-sm text-red-800">{{ $refundRequest->rejection_reason }}</p>
                        </div>
                        @endif

                        @if($refundRequest->processed_at)
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user-check mr-1"></i>
                                Traité le {{ \Carbon\Carbon::parse($refundRequest->processed_at)->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    @if($refundRequest->status == 'pending')
                    <div class="flex gap-3">
                        <button onclick="showApproveModal({{ $refundRequest->id }}, '{{ $refundRequest->participant->user->name }}', {{ $refundRequest->refund_amount }})"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                            <i class="fas fa-check mr-2"></i>Approuver le Remboursement
                        </button>
                        <button onclick="showRejectModal({{ $refundRequest->id }}, '{{ $refundRequest->participant->user->name }}')"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>Rejeter la Demande
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $refundRequests->links() }}
        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune Demande de Remboursement</h3>
            <p class="text-gray-500">
                @if(request('status'))
                    Aucune demande avec ce statut.
                @else
                    Aucune demande de remboursement n'a été soumise pour cet événement.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Approuver le Remboursement</h3>
        <p class="text-gray-600 mb-4">
            Êtes-vous sûr de vouloir approuver le remboursement de <strong><span id="approveName"></span></strong> pour un montant de <strong><span id="approveAmount"></span> {{ config('payments.currency') }}</strong> ?
        </p>
        <form id="approveForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                <textarea name="admin_notes" rows="3" 
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Ajouter des notes pour le participant..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Confirmer l'Approbation
                </button>
                <button type="button" onclick="closeApproveModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Rejeter la Demande</h3>
        <p class="text-gray-600 mb-4">
            Êtes-vous sûr de vouloir rejeter la demande de remboursement de <strong><span id="rejectName"></span></strong> ?
        </p>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Raison du Rejet <span class="text-red-600">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Expliquez pourquoi cette demande est rejetée..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                    Confirmer le Rejet
                </button>
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(refundId, participantName, amount) {
    document.getElementById('approveName').textContent = participantName;
    document.getElementById('approveAmount').textContent = parseFloat(amount).toFixed(3);
    document.getElementById('approveForm').action = `/events/{{ $event->id }}/refund/${refundId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal(refundId, participantName) {
    document.getElementById('rejectName').textContent = participantName;
    document.getElementById('rejectForm').action = `/events/{{ $event->id }}/refund/${refundId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>
@endsection
