<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration PayXora - Plateforme Escrow
    |--------------------------------------------------------------------------
    */

    'commission_rate' => env('PAYXORA_COMMISSION_RATE', 3.0), // % par transaction
    'withdrawal_fee' => env('PAYXORA_WITHDRAWAL_FEE', 500), // FCFA
    'min_transaction_amount' => env('PAYXORA_MIN_AMOUNT', 100), // FCFA
    'max_transaction_amount' => env('PAYXORA_MAX_AMOUNT', 10000000), // FCFA
    'escrow_hold_days' => env('PAYXORA_ESCROW_DAYS', 2), // jours avant auto-liberation
    'dispute_response_hours' => env('PAYXORA_DISPUTE_HOURS', 48), // heures pour repondre

    /*
    |--------------------------------------------------------------------------
    | Mobile Money Providers
    |--------------------------------------------------------------------------
    */
    'payment_providers' => [
        'tmoney' => [
            'name' => 'TMoney (Togocom)',
            'enabled' => env('TMONEY_ENABLED', true),
            'api_url' => env('TMONEY_API_URL'),
            'api_key' => env('TMONEY_API_KEY'),
        ],
        'moov' => [
            'name' => 'Moov Money',
            'enabled' => env('MOOV_ENABLED', true),
            'api_url' => env('MOOV_API_URL'),
            'api_key' => env('MOOV_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | KYC Configuration
    |--------------------------------------------------------------------------
    */
    'kyc' => [
        'required' => true,
        'auto_approve' => env('PAYXORA_KYC_AUTO_APPROVE', false),
        'id_types' => ['passport', 'cni', 'driving_license'],
        'max_file_size' => 2048, // KB
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'email_enabled' => env('PAYXORA_EMAIL_NOTIFICATIONS', true),
        'sms_enabled' => env('PAYXORA_SMS_NOTIFICATIONS', false),
        'brevo_api_key' => env('BREVO_API_KEY'),
    ],
];
