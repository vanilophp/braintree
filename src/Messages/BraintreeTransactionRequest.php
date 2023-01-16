<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use App\Models\Customer;
use Braintree\Result\Error;
use Braintree\Result\Successful;
use Illuminate\Support\Arr;
use Mockery\Exception;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Payment\Contracts\Payment;

class BraintreeTransactionRequest
{
    use HasBraintreeInteraction;

    public function create(Payment $payment, ?string $paymentMethodToken, ?string $paymentMethodNonce, ?string $customerId, bool $saveCard = false): Successful|Error
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
