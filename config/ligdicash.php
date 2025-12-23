<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ligdicash Mode
    |--------------------------------------------------------------------------
    |
    | This option controls the Ligdicash environment mode. Set to 'test' for
    | sandbox testing and 'live' for production.
    |
    */
    'mode' => env('LIGDICASH_MODE', 'test'),

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | Your Ligdicash API credentials. Get these from your Ligdicash
    | dashboard at https://dashboard.ligdicash.com
    |
    */
    'api_key' => env('LIGDICASH_API_KEY'),
    'api_token' => env('LIGDICASH_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | The URL where Ligdicash will send webhook notifications about payment
    | status updates. This must be publicly accessible.
    |
    */
    'callback_url' => env('LIGDICASH_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | Base URLs for Ligdicash API endpoints in different environments.
    |
    */
    'api_url' => [
        'test' => 'https://client.ligdicash.com',
        'live' => 'https://client.ligdicash.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Countries & Operators
    |--------------------------------------------------------------------------
    |
    | Mobile money operators supported by country.
    |
    */
    'countries' => [
        'BF' => [
            'name' => 'Burkina Faso',
            'currency' => 'XOF',
            'phone_code' => '+226',
            'phone_format' => 'XX XX XX XX',
            'operators' => [
                [
                    'code' => 'orange_money_bf',
                    'name' => 'Orange Money',
                    'name_fr' => 'Orange Money',
                    'logo' => '/images/operators/orange-money.png',
                ],
                [
                    'code' => 'moov_money_bf',
                    'name' => 'Moov Money',
                    'name_fr' => 'Moov Money',
                    'logo' => '/images/operators/moov-money.png',
                ],
            ],
        ],
        'CI' => [
            'name' => 'Côte d\'Ivoire',
            'currency' => 'XOF',
            'phone_code' => '+225',
            'phone_format' => 'XX XX XX XX XX',
            'operators' => [
                [
                    'code' => 'orange_money_ci',
                    'name' => 'Orange Money',
                    'name_fr' => 'Orange Money',
                    'logo' => '/images/operators/orange-money.png',
                ],
                [
                    'code' => 'moov_money_ci',
                    'name' => 'Moov Money',
                    'name_fr' => 'Moov Money',
                    'logo' => '/images/operators/moov-money.png',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    */
    'default_country' => 'BF',

    /*
    |--------------------------------------------------------------------------
    | Currency Exchange Rates
    |--------------------------------------------------------------------------
    |
    | Exchange rates for converting USD to local currencies.
    |
    */
    'exchange_rates' => [
        'XOF' => 600, // 1 USD = 600 XOF (approximate)
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    */
    'timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Payment Status Mapping
    |--------------------------------------------------------------------------
    |
    | Map Ligdicash payment statuses to our internal donation statuses.
    |
    */
    'status_mapping' => [
        'pending' => 'pending',
        'completed' => 'completed',
        'failed' => 'failed',
        'cancelled' => 'failed',
    ],
];
