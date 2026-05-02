<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Parametres generaux PayXora
    |--------------------------------------------------------------------------
    */

    // Taux de commission (pourcentage)
    'commission_rate' => env('PAYXORA_COMMISSION_RATE', 3.0),

    // Commission minimum (XOF)
    'commission_minimum' => env('PAYXORA_COMMISSION_MIN', 100),

    // Commission maximum (XOF)
    'commission_maximum' => env('PAYXORA_COMMISSION_MAX', 50000),

    // Delai de confirmation livraison (heures)
    'dispute_response_hours' => env('PAYXORA_DISPUTE_HOURS', 48),

    // Delai d'expiration des transactions non payees (heures)
    'transaction_expiry_hours' => env('PAYXORA_TRANSACTION_EXPIRY_HOURS', 72),

    /*
    |--------------------------------------------------------------------------
    | Provider de paiement par defaut
    |--------------------------------------------------------------------------
    |
    | En environnement local/testing, 'fake' est utilise automatiquement
    | si cette valeur est vide.
    |
    | Valeurs possibles : 'tmoney', 'moov', 'card', 'fake'
    */
    'default_payment_provider' => env('PAYXORA_DEFAULT_PROVIDER', ''),

    /*
    |--------------------------------------------------------------------------
    | Configuration des providers de paiement
    |--------------------------------------------------------------------------
    */

    'payment_providers' => [

        /*
        | TMoney (Togocel)
        */
        'tmoney' => [
            'api_url'    => env('TMONEY_API_URL', ''),
            'api_key'    => env('TMONEY_API_KEY', ''),
            'api_secret' => env('TMONEY_API_SECRET', ''),
            'sandbox'    => env('TMONEY_SANDBOX', true),
        ],

        /*
        | Moov Money (Moov Africa Togo)
        */
        'moov' => [
            'api_url'    => env('MOOV_API_URL', ''),
            'api_key'    => env('MOOV_API_KEY', ''),
            'api_secret' => env('MOOV_API_SECRET', ''),
            'sandbox'    => env('MOOV_SANDBOX', true),
        ],

        /*
        | Carte Bancaire (Stripe, Flutterwave, CinetPay, PayDunya)
        |
        | Le provider actif est determine par 'provider'.
        | Quand tu integres un provider reel, ajoute sa config ici.
        */
        'card' => [
            'provider'       => env('CARD_PROVIDER', 'stripe'), // stripe, flutterwave, cinetpay, paydunya
            'api_key'        => env('CARD_API_KEY', ''),
            'api_secret'     => env('CARD_API_SECRET', ''),
            'webhook_secret' => env('CARD_WEBHOOK_SECRET', ''),
            'sandbox'        => env('CARD_SANDBOX', true),
        ],

        /*
        | Fake Mobile Money (dev uniquement)
        |
        | Ce provider simule TOUJOURS le succes.
        | Ne jamais activer en production.
        */
        'fake' => [
            'enabled' => env('FAKE_PAYMENT_ENABLED', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration Brevo (emails)
    |--------------------------------------------------------------------------
    */

    'brevo' => [
        'api_key' => env('BREVO_API_KEY', ''),
        'from_email' => env('BREVO_FROM_EMAIL', 'noreply@payxora.tg'),
        'from_name' => env('BREVO_FROM_NAME', 'PayXora'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration KYC
    |--------------------------------------------------------------------------
    */

    'kyc' => [
        'required' => env('KYC_REQUIRED', true),
        'auto_approve' => env('KYC_AUTO_APPROVE', false), // En dev uniquement
    ],

    /*
    |--------------------------------------------------------------------------
    | Securite
    |--------------------------------------------------------------------------
    */

    'security' => [
        // Nombre max de tentatives de paiement par transaction
        'max_payment_attempts' => 3,

        // Nombre max de transactions par heure par utilisateur
        'max_transactions_per_hour' => 10,

        // IPs autorisees pour les webhooks (laisser vide pour tout accepter)
        'webhook_allowed_ips' => [],
    ],
];
