<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanbox' => env('MIDTRANS_IS_SANDBOX', !env('MIDTRANS_IS_PRODUCTION')),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    'is_sanitized' => env('MIDTRANS_SANITIZED', true),

    /**
     * Expiry duration in minutes
     * Default: 1440 (24 hours)
     */
    'expiry_duration' => env('MIDTRANS_EXPIRY_DURATION', 1440),

    /**
     * Custom expiry for each payment type
     */
    'custom_expiry' => [
        'credit_card' => 1440,
        'bank_transfer' => 1440,
        'echannel' => 86400, // 60 days for mandiri bill
        'cstore' => 1440,
        'gopay' => 1440,
        'shopeepay' => 1440,
        'qris' => 1440,
    ],

    /**
     * Snap.js URL
     */
    'snap_js_url' => env('MIDTRANS_SNAP_JS', 'https://app.sandbox.midtrans.com/snap/snap.js'),

    /**
     * Midtrans API URLs
     */
    'api_urls' => [
        'sandbox' => [
            'snap' => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
            'core' => 'https://api.sandbox.midtrans.com/v2',
            'status' => 'https://api.sandbox.midtrans.com/v2',
        ],
        'production' => [
            'snap' => 'https://app.midtrans.com/snap/v1/transactions',
            'core' => 'https://api.midtrans.com/v2',
            'status' => 'https://api.midtrans.com/v2',
        ],
    ],

    /**
     * Enable/disable specific payment methods
     */
    'enabled_payments' => [
        'credit_card',
        'gopay',
        'shopeepay',
        'bank_transfer',
        'echannel',
        'bca_klikpay',
        'bca_klikbca',
        'bri_epay',
        'cimb_clicks',
        'danamon_online',
        'cstore',
        'akulaku',
        'qris',
    ],

    /**
     * Payment method restrictions
     */
    'payment_methods' => [
        'credit_card' => [
            'bank' => ['bca', 'bni', 'cimb', 'mandiri'],
            'installment' => [
                'terms' => [3, 6, 12],
                'min_amount' => 500000,
            ],
        ],
        'bank_transfer' => [
            'bca' => [
                'va_number' => '1234567890',
                'sub_company_code' => '00000',
            ],
            'bni' => [
                'va_number' => '12345678',
            ],
            'permata' => [
                'va_number' => '1234567890',
            ],
            'mandiri' => [
                'bill_info1' => 'Payment for:',
                'bill_info2' => 'Sewa Kamera',
            ],
        ],
    ],

    /**
     * Callback URLs
     */
    'callback_urls' => [
        'finish' => '/checkout/success',
        'error' => '/checkout/failed',
        'pending' => '/checkout/pending',
        'notification' => '/midtrans/notification',
    ],

    /**
     * Currency configuration
     */
    'currency' => [
        'code' => 'IDR',
        'symbol' => 'Rp',
    ],
];
