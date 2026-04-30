<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres de commission
    |--------------------------------------------------------------------------
    */
    'commission' => [
        'rate' => 0.03, // 3% par transaction
        'minimum' => 500, // FCFA minimum
        'maximum' => 50000, // FCFA maximum
    ],

    /*
    |--------------------------------------------------------------------------
    | Délai de confirmation de livraison
    |--------------------------------------------------------------------------
    */
    'confirmation_deadline_hours' => 48,

    /*
    |--------------------------------------------------------------------------
    | Limites de transaction
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'min_amount' => 1000, // FCFA
        'max_amount' => 5000000, // FCFA
        'beta_max_amount' => 50000, // FCFA en phase beta
    ],

    /*
    |--------------------------------------------------------------------------
    | Providers Mobile Money
    |--------------------------------------------------------------------------
    */
    'payment_providers' => [
        'tmoney' => [
            'enabled' => true,
            'api_url' => env('TMONEY_API_URL'),
            'api_key' => env('TMONEY_API_KEY'),
            'merchant_id' => env('TMONEY_MERCHANT_ID'),
        ],
        'moov' => [
            'enabled' => true,
            'api_url' => env('MOOV_API_URL'),
            'api_key' => env('MOOV_API_KEY'),
            'merchant_id' => env('MOOV_MERCHANT_ID'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration KYC
    |--------------------------------------------------------------------------
    */
    'kyc' => [
        'required' => true,
        'id_types' => ['cni', 'passport', 'residence'],
        'auto_approve' => false, // false = validation manuelle par admin
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'email' => [
            'enabled' => true,
            'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@payxora.tg'),
            'from_name' => env('MAIL_FROM_NAME', 'PayXora'),
        ],
        'sms' => [
            'enabled' => false, // Activer quand API SMS dispo
            'provider' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Compte séquestre
    |--------------------------------------------------------------------------
    */
    'escrow' => [
        'account_number' => env('ESCROW_ACCOUNT_NUMBER'),
        'bank_name' => env('ESCROW_BANK_NAME', 'ECOBANK'),
        'holder_name' => env('ESCROW_HOLDER_NAME', 'PAYXORA SARL'),
    ],
];
