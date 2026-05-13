<?php

return [
    'merchant_id'     => env('PAYHERE_MERCHANT_ID'),
    'merchant_secret' => env('PAYHERE_MERCHANT_SECRET'),
    'sandbox'         => env('PAYHERE_SANDBOX', true),
    'currency'        => env('PAYHERE_CURRENCY', 'LKR'),

    'sandbox_url'     => 'https://sandbox.payhere.lk/pay/checkout',
    'live_url'        => 'https://www.payhere.lk/pay/checkout',
];