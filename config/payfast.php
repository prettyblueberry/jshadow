<?php

return

/*
|--------------------------------------------------------------------------
| Merchant Settings
|--------------------------------------------------------------------------
| All Merchant settings below are for example purposes only. for more info
| see www.payfast.co.za. The Merchant ID and Merchant Key can be obtained
| from your payfast.co.za account.
|
*/
[
    'testing' => env('PF_TESTING', true), // Set to false when in production.
    'currency' => 'ZAR',
    'merchant' => [
        'merchant_id' => env('PF_MERCHANT_ID', ''), // TEST Credentials. Replace with your merchant ID from Payfast.
        'merchant_key' => env('PF_MERCHANT_KEY', ''), // TEST Credentials. Replace with your merchant key from Payfast.
        // 'merchant_id' => env('PF_SANDBOX_MERCHANT_ID', ''), // TEST Credentials. Replace with your merchant ID from Payfast.
        // 'merchant_key' => env('PF_SANDBOX_MERCHANT_KEY', ''), // TEST Credentials. Replace with your merchant key from Payfast.
        'return_url' => env('PF_RETURN_URL', 'http://your-domain.co.za/success'), // Redirect URL on Success.
        'cancel_url' => env('PF_CANCEL_URL', 'http://your-domain.co.za/cancel'), // Redirect URL on Cancellation.
        'notify_url' => env('PF_ITN_URL', 'http://your-domain.co.za/itn'), // ITN URL.
    ],

    'hosts' => [
        'www.payfast.co.za',
        'sandbox.payfast.co.za',
        'w1w.payfast.co.za',
        'w2w.payfast.co.za',
    ]

];
