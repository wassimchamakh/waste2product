@extends('FrontOffice.layout1.app')

@section('title', 'Demande de Remboursement - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('Events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à l'événement
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Demande de Remboursement</h1>
        </div>

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

        <!-- Event Details Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Détails de l'Événement</h2>
            <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $event->title }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date_start)->format('F j, Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3">Votre Paiement</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Montant Payé:</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($participant->amount_paid, 3) }} {{ config('payments.currency') }}
                        </span>
                    </div>
                    @if($participant->invoice_number)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Numéro de Facture:</span>
                        <span class="font-mono text-xs text-gray-900">{{ $participant->invoice_number }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date de Paiement:</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($participant->payment_completed_at)->format('d/m/Y') }}</span>
                    </div>
                    @if($participant->amount_refunded > 0)
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600">Déjà Remboursé:</span>
                        <span class="font-semibold text-red-600">
                            -{{ number_format($participant->amount_refunded, 3) }} {{ config('payments.currency') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Refund Amount Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">Calcul du Remboursement</h3>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-gray-700">
                    <span>Paiement Initial:</span>
                    <span class="font-medium">{{ number_format($participant->amount_paid, 3) }} {{ config('payments.currency') }}</span>
                </div>
                @if($participant->amount_refunded > 0)
                <div class="flex justify-between text-gray-700">
                    <span>Remboursements Précédents:</span>
                    <span class="font-medium text-red-600">-{{ number_format($participant->amount_refunded, 3) }} {{ config('payments.currency') }}</span>
                </div>
                @endif
                @if($refundPercentage < 100)
                <div class="flex justify-between text-gray-700">
                    <span>Pourcentage de Remboursement:</span>
                    <span class="font-medium">{{ $refundPercentage }}%</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold text-blue-900 border-t border-blue-300 pt-3">
                    <span>Montant Remboursable:</span>
                    <span>{{ number_format($eligibleRefundAmount, 3) }} {{ config('payments.currency') }}</span>
                </div>
            </div>

            <!-- Cancellation Policy Info -->
            <div class="bg-white rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-2">Politique d'Annulation: {{ $event->getCancellationPolicyLabel() }}</h4>
                <p class="text-sm text-gray-600">
                    @if($event->cancellation_policy === 'flexible')
                        Remboursement intégral si annulé au moins 24 heures avant l'événement.
                    @elseif($event->cancellation_policy === 'moderate')
                        Remboursement intégral si annulé au moins 5 jours avant l'événement; remboursement de 50% si annulé dans les 5 jours.
                    @elseif($event->cancellation_policy === 'strict')
                        Remboursement intégral si annulé au moins 7 jours avant l'événement; aucun remboursement si annulé dans les 7 jours.
                    @elseif($event->cancellation_policy === 'custom')
                        Un calendrier de remboursement personnalisé s'applique selon le moment d'annulation.
                    @elseif($event->cancellation_policy === 'no_refund')
                        Aucun remboursement autorisé pour cet événement.
                    @endif
                </p>
                
                @if($daysUntilEvent !== null)
                <div class="mt-3 flex items-center text-sm">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-gray-700">
                        <strong>{{ $daysUntilEvent }}</strong> {{ $daysUntilEvent == 1 ? 'jour' : 'jours' }} avant l'événement
                    </span>
                </div>
                @endif
            </div>
        </div>

        @if($eligibleRefundAmount > 0)
        <!-- Refund Request Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Soumettre une Demande de Remboursement</h2>
            
            <form action="{{ route('Events.refund.request', [$event->id, $participant->id]) }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Refund Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type de Remboursement <span class="text-red-600">*</span>
                    </label>
                    <select name="refund_type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="full">Remboursement Intégral ({{ number_format($eligibleRefundAmount, 3) }} {{ config('payments.currency') }})</option>
                        @if($eligibleRefundAmount > 10)
                        <option value="partial">Remboursement Partiel</option>
                        @endif
                    </select>
                </div>

                <!-- Partial Amount (shown conditionally via JavaScript) -->
                <div id="partial-amount-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Montant du Remboursement <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            name="partial_amount" 
                            step="0.001" 
                            min="1" 
                            max="{{ $eligibleRefundAmount }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-16 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Entrez le montant"
                        >
                        <span class="absolute right-3 top-2 text-gray-500">{{ config('payments.currency') }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Maximum: {{ number_format($eligibleRefundAmount, 3) }} {{ config('payments.currency') }}</p>
                </div>

                <!-- Reason Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Raison du Remboursement <span class="text-red-600">*</span>
                    </label>
                    <select name="reason_category" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Sélectionnez une raison</option>
                        <option value="event_cancelled">Événement Annulé</option>
                        <option value="event_rescheduled">Événement Reprogrammé</option>
                        <option value="personal_emergency">Urgence Personnelle</option>
                        <option value="cannot_attend">Impossible d'Assister</option>
                        <option value="dissatisfied">Insatisfait</option>
                        <option value="other">Autre</option>
                    </select>
                </div>

                <!-- Detailed Reason -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Détails Supplémentaires <span class="text-red-600">*</span>
                    </label>
                    <textarea 
                        name="reason" 
                        rows="4" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Veuillez fournir toute information supplémentaire concernant votre demande de remboursement..."
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">Cela aide l'organisateur à comprendre votre situation.</p>
                </div>

                <!-- Important Notice -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-yellow-900 mb-1">Informations Importantes</h4>
                            <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                                <li>Les demandes de remboursement nécessitent l'approbation de l'organisateur</li>
                                <li>Le traitement peut prendre 5 à 10 jours ouvrables après approbation</li>
                                <li>Les remboursements seront émis vers votre méthode de paiement d'origine</li>
                                <li>Vous recevrez une notification par email de l'approbation/rejet</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        Soumettre la Demande
                    </button>
                    <a 
                        href="{{ route('Events.show', $event->id) }}" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg text-center transition duration-200"
                    >
                        Annuler
                    </a>
                </div>
            </form>
        </div>
        @else
        <!-- No Refund Available -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
            <svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-red-900 mb-2">Remboursement Non Disponible</h3>
            <p class="text-red-800 mb-4">
                Selon la politique d'annulation de l'événement et le timing, vous n'êtes pas éligible à un remboursement pour le moment.
            </p>
            <a 
                href="{{ route('Events.show', $event->id) }}" 
                class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
            >
                Retour à l'Événement
            </a>
        </div>
        @endif
    </div>
</div>

<!-- JavaScript for Partial Refund Toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const refundTypeSelect = document.querySelector('select[name="refund_type"]');
        const partialAmountSection = document.getElementById('partial-amount-section');
        const partialAmountInput = document.querySelector('input[name="partial_amount"]');
        
        if (refundTypeSelect) {
            refundTypeSelect.addEventListener('change', function() {
                if (this.value === 'partial') {
                    partialAmountSection.classList.remove('hidden');
                    partialAmountInput.required = true;
                } else {
                    partialAmountSection.classList.add('hidden');
                    partialAmountInput.required = false;
                }
            });
        }
    });
</script>
@endsection
