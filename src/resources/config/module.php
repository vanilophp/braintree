<?php

declare(strict_types=1);

use Vanilo\Braintree\BraintreePaymentGateway;

return [
    'gateway' => [
        'register' => true,
        'id' => BraintreePaymentGateway::DEFAULT_ID
    ],
    'bind' => true,
    'xxx' => env('BRAINTREE_Braintree'),
];
