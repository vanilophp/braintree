<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Exception\NotFound;
use Braintree\Result\Error;
use Braintree\Result\Successful;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreeRefundTransactionRequest
{
    use HasBraintreeInteraction;

    /**
     * @throws NotFound
     */
    public function refund(string $transactionId, ?float $amount = null): Error|Successful
    {
        return $this->gateway->transaction()->refund($transactionId, $amount);
    }
}
