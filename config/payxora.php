<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Commission & Tarification
    |--------------------------------------------------------------------------
    */
    'commission_rate' => env('PAYXORA_COMMISSION_RATE', 3.0), // % par transaction
    'commission_minimum' => env('PAYXORA_COMMISSION_MIN', 100), // FCFA
    'commission_maximum' => env('PAYXORA_COMMISSION_MAX', 50000), // FCFA
    'withdrawal_fee' => env('PAYXORA_WITHDRAWAL_FEE', 500), // FCFA
    'min_transaction_amount' => env('PAYXORA_MIN_AMOUNT', 1000), // FCFA
    'max_transaction_amount' => env('PAYXORA_MAX_AMOUNT', 10000000), // FCFA

    /*
    |--------------------------------------------------------------------------
    | Delais & Sequestre
    |--------------------------------------------------------------------------
    */
    'escrow_hold_days' => env('PAYXORA_ESCROW_DAYS', 2), // jours avant auto-liberation
    'dispute_response_hours' => env('PAYXORA_DISPUTE_HOURS', 48), // heures pour repondre
    'confirmation_deadline_hours' => env('PAYXORA_CONFIRM_HOURS', 48), // heures pour confirmer reception
    'auto_expire_hours' => env('PAYXORA_EXPIRE_HOURS', 72), // heures avant annulation auto si non payee

    /*
    |--------------------------------------------------------------------------
    | Mobile Money Providers
    |--------------------------------------------------------------------------
    */
    'payment_providers' => [
        'tmoney' => [
            'name' => 'TMoney (Togocom)',
            'enabled' => env('TMONEY_ENABLED', false),
            'api_url' => env('TMONEY_API_URL'),
            'api_key' => env('TMONEY_API_KEY'),
            'api_secret' => env('TMONEY_API_SECRET'),
        ],
        'moov' => [
            'name' => 'Moov Money',
            'enabled' => env('MOOV_ENABLED', false),
            'api_url' => env('MOOV_API_URL'),
            'api_key' => env('MOOV_API_KEY'),
            'api_secret' => env('MOOV_API_SECRET'),
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
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
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
        'brevo_sender_email' => env('BREVO_SENDER_EMAIL', 'noreply@payxora.tg'),
        'brevo_sender_name' => env('BREVO_SENDER_NAME', 'PayXora'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Securite
    |--------------------------------------------------------------------------
    */
    'security' => [
        'max_login_attempts' => 5,
        'lockout_duration' => 15, // minutes
        'session_timeout' => 120, // minutes
        'require_2fa' => env('PAYXORA_REQUIRE_2FA', false),
    ],
];
