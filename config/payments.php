<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active gateway
    |--------------------------------------------------------------------------
    |
    | Only hosted-page gateways belong here: the card is entered on the
    | provider's page, so no card data ever reaches this application and the
    | site stays out of PCI DSS scope beyond SAQ A.
    |
    */

    'gateway' => env('PAYMENT_GATEWAY', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Every plan is priced in one currency. Amounts are stored in minor units
    | (fils for AED) so no float rounding can ever change what the student owes.
    |
    */

    'currency' => env('PAYMENT_CURRENCY', 'AED'),

    'gateways' => [

        'stripe' => [
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
            'api_base' => env('STRIPE_API_BASE', 'https://api.stripe.com'),
            // Seconds a webhook timestamp may differ from ours.
            'webhook_tolerance' => 300,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Public payment page
    |--------------------------------------------------------------------------
    |
    | The page is reachable only with a random token and is never linked from
    | the site. These values control how long a plan stays payable and what the
    | student is told when a plan is closed.
    |
    */

    'page' => [
        // A plan past this many days beyond its final due date stops accepting
        // new card payments, so stale links cannot be used months later.
        'grace_days' => (int) env('PAYMENT_GRACE_DAYS', 60),
        'support_email' => env('PAYMENT_SUPPORT_EMAIL') ?: 'admissions@mbalondon.org.uk',
        'support_phone' => env('PAYMENT_SUPPORT_PHONE') ?: '+44 20 4538 5255',
    ],

];
