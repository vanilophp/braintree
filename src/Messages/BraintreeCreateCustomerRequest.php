<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Customer;
use Braintree\Exception\NotFound;
use Braintree\Result\Successful;
use Illuminate\Support\Arr;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;

class BraintreeCreateCustomerRequest
{
    use HasBraintreeInteraction;

    public function create(string $firstName, string $lastName, string $email, ?string $phone, ?string $companyName): ?Customer
    {
        $response = $this->gateway->customer()->create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'company' => $companyName,
            'phone' => $phone,
            'email' => $email,
        ]);

        if ($response instanceof Successful) {
            return $response->customer;
        }

        return null;
    }
}
