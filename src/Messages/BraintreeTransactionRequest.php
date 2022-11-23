<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Result\Error;
use Braintree\Result\Successful;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Payment\Contracts\Payment;

class BraintreeTransactionRequest
{
    use HasBraintreeInteraction;

    public function create(Payment $payment, string $nonce): Successful|Error
    {
        $billPayer = $payment->getPayable()->getBillpayer();

        $request = [
            'orderId' => $payment->getPayable()->getPayableId(),
            'amount' => $payment->getAmount(),
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true,
            ],
        ];

        if ($billPayer) {
            $request['customer'] = [
                'firstName' => $billPayer->getFirstName(),
                'lastName' => $billPayer->getLastName(),
                'company' => $billPayer->getCompanyName(),
                'phone' => $billPayer->getPhone(),
                'email' => $billPayer->getEmail(),
            ];
        }

        return $this->gateway->transaction()->sale($request);
    }
}
