<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Exception\NotFound;
use Braintree\Transaction;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreeGetTransactionRequest
{
    use HasBraintreeInteraction;

    public function get(string $transactionId): ?Transaction
    {
        try {
            return $this->gateway->transaction()->find($transactionId);
        } catch (NotFound $e) {
            return null;
        }
    }
}
