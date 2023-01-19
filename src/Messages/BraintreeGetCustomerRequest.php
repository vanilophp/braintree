<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Customer;
use Braintree\Exception\NotFound;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreeGetCustomerRequest
{
    use HasBraintreeInteraction;

    public function get(string $customerId): ?Customer
    {
        try {
            return  $this->gateway->customer()->find($customerId);
        } catch (NotFound $e) {
            return null;
        }
    }
}
