<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Result\Error;
use Braintree\Result\Successful;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;

class BraintreeTransactionRequest
{
    use HasBraintreeInteraction;

    public function create(Payment $payment, Address $billingAddress, ?string $paymentMethodToken, ?string $paymentMethodNonce, ?string $customerId, bool $saveCard = false): Successful|Error
    {
        $request = [
            'orderId' => $payment->getPayable()->getPayableId(),
            'amount' => $payment->getAmount(),
            'options' => [
                'submitForSettlement' => true,
                'storeInVaultOnSuccess' => $saveCard,
            ],
            'customFields' => [
                'payment_id' => $payment->getPaymentId(),
            ],
        ];

        if ($saveCard) {
            $request = Arr::add($request, 'billing', [
                'firstName' => Str::before($billingAddress->getName(), ' '),
                'lastName' => Str::after($billingAddress->getName(), ' '),
                'streetAddress' => $billingAddress->getAddress(),
                'locality' => $billingAddress->getCity(),
                'region' => $billingAddress->getProvinceCode(),
                'postalCode' => $billingAddress->getPostalCode(),
                'countryCodeAlpha2' => $billingAddress->getCountryCode(),
            ]);
        }

        if ($paymentMethodToken) {
            $request = Arr::add($request, 'paymentMethodToken', $paymentMethodToken);
        } else {
            $request = Arr::add($request, 'paymentMethodNonce', $paymentMethodNonce);
        }

        if ($customerId) {
            $request['customerId'] = $customerId;
        }

        return $this->gateway->transaction()->sale($request);
    }
}
