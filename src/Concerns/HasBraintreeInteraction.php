<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Concerns;

use Braintree\Gateway;

trait HasBraintreeInteraction
{
    private Gateway $gateway;

    public function __construct(
        private bool $isTest,
        private string $merchantId,
        private string $publicKey,
        private string $privateKey,
    ) {
        $this->gateway = new Gateway([
            'environment' => $isTest ? 'sandbox' : 'production',
            'merchantId' => $merchantId,
            'publicKey' => $publicKey,
            'privateKey' => $privateKey,
        ]);
    }
}
