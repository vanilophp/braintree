<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreeClientTokenRequest
{
    use HasBraintreeInteraction;

    public function get(): string
    {
        return $this->gateway->clientToken()->generate();
    }
}
