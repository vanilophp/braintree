<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Exception\NotFound;
use Braintree\Transaction;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreeRefundTransactionRequest
{
    use HasBraintreeInteraction;

    public function refund(string $transactionId, ?double $amount = null): ?Transaction
    {
        try {
            return $this->gateway->transaction()->refund($transactionId, $amount);
        } catch (NotFound $e) {
            return null;
        }
    }
}
