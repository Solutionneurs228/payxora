<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres généraux PayXora
    |--------------------------------------------------------------------------
    */

    'app_name' => env('PAYXORA_APP_NAME', 'PayXora'),
    'app_url' => env('PAYXORA_APP_URL', 'https://payxora.tg'),
    'support_email' => env('PAYXORA_SUPPORT_EMAIL', 'support@payxora.tg'),

    /*
    |--------------------------------------------------------------------------
    | Commission
    |--------------------------------------------------------------------------
    */

    'commission_rate' => env('PAYXORA_COMMISSION_RATE', 3.0),
    'commission_minimum' => env('PAYXORA_COMMISSION_MINIMUM', 100),
    'commission_maximum' => env('PAYXORA_COMMISSION_MAXIMUM', 50000),

    /*
    |--------------------------------------------------------------------------
    | Délai de confirmation livraison (heures)
    |--------------------------------------------------------------------------
    */

    'dispute_response_hours' => env('PAYXORA_DISPUTE_HOURS', 48),
    'transaction_expiry_hours' => env('PAYXORA_TRANSACTION_EXPIRY_HOURS', 72),

    /*
    |--------------------------------------------------------------------------
    | Provider de paiement par défaut
    |--------------------------------------------------------------------------
    */

    'default_payment_provider' => env('PAYXORA_DEFAULT_PAYMENT_PROVIDER', 'fake'),

    /*
    |--------------------------------------------------------------------------
    | Mobile Money
    |--------------------------------------------------------------------------
    */

    'tmoney' => [
        'enabled' => env('TMONEY_ENABLED', false),
        'api_url' => env('TMONEY_API_URL', 'https://api.tmoney.tg/sandbox'),
        'merchant_id' => env('TMONEY_MERCHANT_ID'),
        'api_key' => env('TMONEY_API_KEY'),
        'secret' => env('TMONEY_SECRET'),
        'sandbox' => env('TMONEY_SANDBOX', true),
    ],

    'moov' => [
        'enabled' => env('MOOV_ENABLED', false),
        'api_url' => env('MOOV_API_URL', 'https://api.moov.tg/sandbox'),
        'merchant_id' => env('MOOV_MERCHANT_ID'),
        'api_key' => env('MOOV_API_KEY'),
        'secret' => env('MOOV_SECRET'),
        'sandbox' => env('MOOV_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cartes bancaires
    |--------------------------------------------------------------------------
    */

    'stripe' => [
        'enabled' => env('STRIPE_ENABLED', false),
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'sandbox' => env('STRIPE_SANDBOX', true),
    ],

    'flutterwave' => [
        'enabled' => env('FLUTTERWAVE_ENABLED', false),
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),
        'sandbox' => env('FLUTTERWAVE_SANDBOX', true),
    ],

    'cinetpay' => [
        'enabled' => env('CINETPAY_ENABLED', false),
        'api_key' => env('CINETPAY_API_KEY'),
        'site_id' => env('CINETPAY_SITE_ID'),
        'secret' => env('CINETPAY_SECRET'),
        'sandbox' => env('CINETPAY_SANDBOX', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    'brevo' => [
        'api_key' => env('BREVO_API_KEY'),
        'sender_email' => env('BREVO_SENDER_EMAIL', 'noreply@payxora.tg'),
        'sender_name' => env('BREVO_SENDER_NAME', 'PayXora'),
    ],

    /*
    |--------------------------------------------------------------------------
    | KYC
    |--------------------------------------------------------------------------
    */

    'kyc' => [
        'required_for_transaction' => env('PAYXORA_KYC_REQUIRED', true),
        'document_types' => ['cni', 'passport', 'driver_license'],
        'max_file_size_kb' => 5120,
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sécurité
    |--------------------------------------------------------------------------
    */

    'security' => [
        'max_login_attempts' => 5,
        'lockout_duration_minutes' => 15,
        'password_min_length' => 8,
        'require_special_char' => true,
        'session_timeout_minutes' => 120,
    ],

    /*
    |--------------------------------------------------------------------------
    | Retraits
    |--------------------------------------------------------------------------
    */

    'withdrawal' => [
        'minimum_amount' => 1000,
        'maximum_amount' => 5000000,
        'processing_hours' => 24,
        'fee_rate' => 1.0,
    ],
];
