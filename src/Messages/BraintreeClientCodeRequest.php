<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Illuminate\Support\Facades\View;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Payment\Contracts\PaymentRequest;

class BraintreeClientCodeRequest
{
    use HasBraintreeInteraction;

    public function get(): string
    {
        return $this->gateway->clientToken()->generate();
    }
}
