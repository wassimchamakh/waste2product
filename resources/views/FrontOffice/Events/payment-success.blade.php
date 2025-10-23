@extends('FrontOffice.layout1.app')

@section('title', 'Paiement Réussi')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Paiement Réussi!</h1>
            <p class="text-lg text-gray-600">Merci pour votre inscription</p>
        </div>

        <!-- Event Details Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Inscription Confirmée</h2>
            
            <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $event->title }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date_start)->format('d/m/Y') }}</span>
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

            <!-- Payment Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <h4 class="font-semibold text-gray-700 mb-3">Détails du Paiement</h4>
                <div class="space-y-2 text-sm">
                    @if($participant->invoice_number)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Numéro de Facture:</span>
                        <span class="font-mono font-medium text-gray-900">{{ $participant->invoice_number }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID de Transaction:</span>
                        <span class="font-mono text-xs text-gray-900">{{ $participant->payment_intent_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Montant Payé:</span>
                        <span class="font-semibold text-gray-900">
                            {{ number_format($participant->amount_paid, 3) }} {{ config('payments.currency') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Méthode de Paiement:</span>
                        <span class="text-gray-900 capitalize">{{ $participant->payment_method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date de Paiement:</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($participant->payment_completed_at)->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Participant Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-3">Vos Informations</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nom:</span>
                        <span class="text-gray-900">{{ $participant->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="text-gray-900">{{ $participant->user->email }}</span>
                    </div>
                    @if($participant->user->phone)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Téléphone:</span>
                        <span class="text-gray-900">{{ $participant->user->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">Prochaines Étapes</h3>
            <ul class="space-y-3 text-gray-600">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Un email de confirmation a été envoyé à <strong>{{ $participant->user->email }}</strong> avec les détails de votre inscription.</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Vous recevrez les mises à jour de l'événement et des rappels par email.</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Veuillez conserver votre numéro de facture pour vos dossiers.</span>
                </li>
                @if($event->cancellation_policy && $event->cancellation_policy !== 'no_refund')
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Si vous devez annuler, vous pouvez demander un remboursement selon la {{ strtolower($event->getCancellationPolicyLabel()) }} de l'événement.</span>
                </li>
                @endif
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('Events.show', $event->id) }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Voir Détails de l'Événement
            </a>
            <a href="{{ route('Events.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition duration-200">
                Parcourir Plus d'Événements
            </a>
        </div>

        <!-- Support Info -->
        <div class="mt-8 text-center text-sm text-gray-600">
            <p>Besoin d'aide? Contactez l'organisateur de l'événement ou notre équipe de support.</p>
        </div>
    </div>
</div>
@endsection
