<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Result\Error;
use Braintree\Result\Successful;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreePaymentMethodDeleteRequest
{
    use HasBraintreeInteraction;

    public function delete(string $token): Error|Successful
    {
        return $this->gateway->paymentMethod()->delete($token);
    }
}
