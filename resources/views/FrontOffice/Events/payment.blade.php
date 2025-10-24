@extends('FrontOffice.layout1.app')

@section('title', 'Paiement - ' . $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Event Details Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Finaliser Votre Inscription</h1>
            
            <div class="border-b pb-4 mb-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">{{ $event->title }}</h2>
                <div class="flex items-center text-gray-600 mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ \Carbon\Carbon::parse($event->date_start)->format('d/m/Y') }}
                    @if($event->date_end)
                        - {{ \Carbon\Carbon::parse($event->date_end)->format('d/m/Y') }}
                    @endif
                </div>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $event->location }}
                </div>
            </div>

            <!-- Participant Details -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-700 mb-3">Informations du Participant</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Nom:</span>
                        <span class="ml-2 font-medium">{{ $participant->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Email:</span>
                        <span class="ml-2 font-medium">{{ $participant->user->email }}</span>
                    </div>
                    @if($participant->user->phone)
                    <div>
                        <span class="text-gray-600">Téléphone:</span>
                        <span class="ml-2 font-medium">{{ $participant->user->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Détails du Paiement</h2>

            <!-- Price Breakdown -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700">Frais d'Inscription:</span>
                    <span class="text-lg font-semibold text-gray-900">
                        {{ number_format($amount, 3) }} {{ config('payments.currency') }}
                    </span>
                </div>
                @if($event->hasActiveEarlyBird())
                <div class="flex justify-between items-center text-sm text-green-600">
                    <span>✓ Réduction Inscription Anticipée Appliquée</span>
                    <span>-{{ number_format($event->price - $event->early_bird_price, 3) }} {{ config('payments.currency') }}</span>
                </div>
                @endif
                @if($event->group_discount_size && $participant->group_size >= $event->group_discount_size)
                <div class="flex justify-between items-center text-sm text-green-600">
                    <span>✓ Réduction de Groupe Appliquée</span>
                    <span>-{{ number_format($event->price * $event->group_discount_percent / 100, 3) }} {{ config('payments.currency') }}</span>
                </div>
                @endif
            </div>

            @if($paymentDeadline)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6 text-sm text-yellow-800">
                <strong>⏰ Date Limite de Paiement:</strong> Vous devez effectuer le paiement avant le {{ \Carbon\Carbon::parse($paymentDeadline)->format('d/m/Y à H:i') }}
            </div>
            @endif

            <!-- Stripe Payment Form -->
            <form id="payment-form" class="space-y-4">
                @csrf
                
                <!-- Card Element Container -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Informations de la Carte
                    </label>
                    <div id="card-element" class="border border-gray-300 rounded-md p-3 bg-white">
                        <!-- Stripe Card Element will be inserted here -->
                    </div>
                    <div id="card-errors" class="text-red-600 text-sm mt-2"></div>
                </div>

                <!-- Payment Button -->
                <button 
                    type="submit" 
                    id="submit-button"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center"
                    disabled
                >
                    <svg id="spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="button-text">Payer {{ number_format($amount, 3) }} {{ config('payments.currency') }}</span>
                </button>

                <div class="text-center">
                    <a href="{{ route('Events.show', $event->id) }}" class="text-gray-600 hover:text-gray-800 text-sm">
                        Annuler et retourner à l'événement
                    </a>
                </div>
            </form>

            <!-- Security Notice -->
            <div class="mt-6 flex items-start text-xs text-gray-500">
                <svg class="w-4 h-4 mr-2 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                <span>Vos informations de paiement sont cryptées et sécurisées. Nous utilisons Stripe pour le traitement des paiements et ne stockons jamais les détails de votre carte.</span>
            </div>
        </div>

        <!-- Cancellation Policy -->
        @if($event->cancellation_policy && $event->cancellation_policy !== 'no_refund')
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="font-semibold text-gray-800 mb-3">Politique d'Annulation</h3>
            <p class="text-gray-600 text-sm">
                <strong>{{ $event->getCancellationPolicyLabel() }}</strong>
                @if($event->cancellation_policy === 'flexible')
                - Remboursement intégral si annulé au moins 24 heures avant l'événement.
                @elseif($event->cancellation_policy === 'moderate')
                - Remboursement intégral si annulé au moins 5 jours avant l'événement; remboursement de 50% si annulé dans les 5 jours.
                @elseif($event->cancellation_policy === 'strict')
                - Remboursement intégral si annulé au moins 7 jours avant l'événement; aucun remboursement dans les 7 jours.
                @elseif($event->cancellation_policy === 'custom')
                - Un calendrier de remboursement personnalisé s'applique. Contactez l'organisateur pour plus de détails.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    
    // Mount card element
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        const submitButton = document.getElementById('submit-button');
        
        if (event.error) {
            displayError.textContent = event.error.message;
            submitButton.disabled = true;
        } else {
            displayError.textContent = '';
            submitButton.disabled = false;
        }
    });
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Disable submit button and show spinner
        submitButton.disabled = true;
        spinner.classList.remove('hidden');
        buttonText.textContent = 'Traitement...';
        
        try {
            // Confirm the payment with Stripe
            const { error, paymentIntent } = await stripe.confirmCardPayment(
                '{{ $clientSecret }}',
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: '{{ $participant->user->name }}',
                            email: '{{ $participant->user->email }}',
                            @if($participant->user->phone)
                            phone: '{{ $participant->user->phone }}'
                            @endif
                        }
                    }
                }
            );
            
            if (error) {
                // Show error to customer
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                
                // Re-enable submit button
                submitButton.disabled = false;
                spinner.classList.add('hidden');
                buttonText.textContent = 'Payer {{ number_format($amount, 3) }} {{ config('payments.currency') }}';
            } else {
                // Payment successful - Update payment status via AJAX before redirect
                try {
                    const response = await fetch('{{ route("Events.payment.success", ["event" => $event->id, "participant" => $participant->id]) }}?payment_intent=' + paymentIntent.id + '&confirm=1', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    // Redirect to success page
                    window.location.href = '{{ route("Events.payment.success", ["event" => $event->id, "participant" => $participant->id]) }}?payment_intent=' + paymentIntent.id;
                } catch (fetchError) {
                    console.error('Error updating payment:', fetchError);
                    // Still redirect even if update fails
                    window.location.href = '{{ route("Events.payment.success", ["event" => $event->id, "participant" => $participant->id]) }}?payment_intent=' + paymentIntent.id;
                }
            }
        } catch (err) {
            console.error('Payment error:', err);
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = 'An unexpected error occurred. Please try again.';
            
            // Re-enable submit button
            submitButton.disabled = false;
            spinner.classList.add('hidden');
            buttonText.textContent = 'Pay {{ number_format($amount, 3) }} {{ config('payments.currency') }}';
        }
    });
</script>
@endsection
