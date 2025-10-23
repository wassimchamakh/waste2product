<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Provider
    |--------------------------------------------------------------------------
    |
    | This option defines the default payment provider that will be used
    | by the application. You may set this to any of the providers
    | supported by your application.
    |
    | Supported: "stripe", "paypal", "flouci", "bank_transfer"
    |
    */

    'default' => env('PAYMENT_PROVIDER', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Payment Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many payment providers as you wish.
    |
    */

    'providers' => [
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', true),
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'currency' => env('STRIPE_CURRENCY', 'tnd'),
            'fee_percent' => 2.9, // Stripe fee percentage
            'fee_fixed' => 0.30,   // Stripe fixed fee per transaction
        ],

        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'currency' => env('PAYPAL_CURRENCY', 'USD'),
            'fee_percent' => 3.4,
            'fee_fixed' => 0.30,
        ],

        'flouci' => [
            'enabled' => env('FLOUCI_ENABLED', false),
            'app_token' => env('FLOUCI_APP_TOKEN'),
            'app_secret' => env('FLOUCI_APP_SECRET'),
            'api_url' => env('FLOUCI_API_URL', 'https://developers.flouci.com/api/'),
            'currency' => 'TND',
            'fee_percent' => 1.5, // Flouci typical fee
        ],

        'bank_transfer' => [
            'enabled' => env('BANK_TRANSFER_ENABLED', true),
            'bank_name' => env('BANK_NAME', 'Votre Banque'),
            'account_holder' => env('BANK_ACCOUNT_HOLDER', 'Waste2Product'),
            'account_number' => env('BANK_ACCOUNT_NUMBER'),
            'iban' => env('BANK_IBAN'),
            'swift' => env('BANK_SWIFT'),
            'manual_verification' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Platform Commission
    |--------------------------------------------------------------------------
    |
    | The percentage of each transaction that goes to your platform.
    | This is separate from payment provider fees.
    |
    */

    'platform_fee_percent' => env('PLATFORM_FEE_PERCENT', 5),

    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    */

    'settings' => [
        // Default payment deadline in hours
        'default_payment_deadline_hours' => 24,

        // Minimum event price
        'minimum_price' => 1.00,

        // Maximum event price
        'maximum_price' => 10000.00,

        // Default cancellation policy
        'default_cancellation_policy' => 'flexible',

        // Allow refunds after event start
        'allow_refunds_after_event_start' => false,

        // Automatic refund approval for organizer cancelled events
        'auto_approve_organizer_cancellations' => true,

        // Days to process refund
        'refund_processing_days' => 7,

        // Generate invoices automatically
        'auto_generate_invoices' => true,

        // Invoice prefix
        'invoice_prefix' => 'INV',

        // Send invoice email automatically
        'auto_send_invoice_email' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    */

    'currency' => env('PAYMENT_CURRENCY', 'TND'),
    'currency_symbol' => env('PAYMENT_CURRENCY_SYMBOL', 'TND'),
    'currency_decimals' => 3,

    /*
    |--------------------------------------------------------------------------
    | Webhook URLs
    |--------------------------------------------------------------------------
    |
    | These are the URLs that payment providers will call for notifications.
    |
    */

    'webhooks' => [
        'stripe' => env('APP_URL') . '/webhooks/stripe',
        'paypal' => env('APP_URL') . '/webhooks/paypal',
        'flouci' => env('APP_URL') . '/webhooks/flouci',
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods Display Names
    |--------------------------------------------------------------------------
    */

    'method_labels' => [
        'stripe' => 'Carte Bancaire (Visa, Mastercard)',
        'paypal' => 'PayPal',
        'flouci' => 'Flouci (Mobile Payment)',
        'bank_transfer' => 'Virement Bancaire',
        'cash' => 'Paiement en espèces',
    ],

    /*
    |--------------------------------------------------------------------------
    | Testing Mode
    |--------------------------------------------------------------------------
    |
    | When testing mode is enabled, actual payments won't be processed.
    | Useful for development and testing.
    |
    */

    'testing_mode' => env('PAYMENT_TESTING_MODE', false),

];
