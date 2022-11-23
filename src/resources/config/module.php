<?php

declare(strict_types=1);

use Vanilo\Braintree\BraintreePaymentGateway;

return [
    'gateway' => [
        'register' => true,
        'id' => BraintreePaymentGateway::DEFAULT_ID,
    ],
    'bind' => true,
    'is_test' => (bool)env('BRAINTREE_IS_TEST', true),
    'merchant_id' => env('BRAINTREE_MERCHANT_ID'),
    'public_key' => env('BRAINTREE_PUBLIC_KEY'),
    'private_key' => env('BRAINTREE_PRIVATE_KEY'),
];
