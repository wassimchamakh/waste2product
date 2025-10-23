@extends('FrontOffice.layout1.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <!-- Failure Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-4">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Payment Failed</h1>
            <p class="text-lg text-gray-600">We couldn't process your payment</p>
        </div>

        <!-- Error Details Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            @if(isset($error))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-red-900 mb-1">Error Details</h3>
                        <p class="text-red-800">{{ $error }}</p>
                    </div>
                </div>
            </div>
            @endif

            <h2 class="text-xl font-semibold text-gray-800 mb-4">What Happened?</h2>
            <p class="text-gray-600 mb-6">
                Your payment could not be completed. This might have happened for several reasons:
            </p>

            <ul class="space-y-3 text-gray-600 mb-6">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Insufficient funds in your account</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Incorrect card details entered</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Card declined by your bank</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Network or connection issue</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Card expired or cancelled</span>
                </li>
            </ul>

            <div class="border-t pt-6">
                <h3 class="font-semibold text-gray-800 mb-3">Event Information</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">{{ $event->title }}</h4>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $event->date_debut->format('F j, Y') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ number_format($participant->amount_paid ?? $event->getCurrentPrice(), 3) }} {{ config('payments.currency') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-blue-900 mb-3">Need Assistance?</h3>
            <p class="text-blue-800 mb-4">
                If you continue to experience issues with payment, please try the following:
            </p>
            <ul class="space-y-2 text-blue-800 text-sm">
                <li class="flex items-start">
                    <span class="font-bold mr-2">1.</span>
                    <span>Contact your bank to ensure international or online transactions are enabled</span>
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">2.</span>
                    <span>Try using a different payment card</span>
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">3.</span>
                    <span>Check that you have sufficient funds available</span>
                </li>
                <li class="flex items-start">
                    <span class="font-bold mr-2">4.</span>
                    <span>Contact our support team if the problem persists</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if(isset($participant))
            <a href="{{ route('Events.payment', $event->id) }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Try Again
            </a>
            @endif
            <a href="{{ route('Events.show', $event->id) }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Event
            </a>
        </div>

        <!-- Security Notice -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p class="flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                Your card was not charged. All payment information is securely encrypted.
            </p>
        </div>
    </div>
</div>
@endsection
