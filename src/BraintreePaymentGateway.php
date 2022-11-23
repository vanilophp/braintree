<?php

declare(strict_types=1);

namespace Vanilo\Braintree;

use Illuminate\Http\Request;
use Vanilo\Braintree\Messages\BraintreePaymentRequest;
use Vanilo\Braintree\Messages\BraintreePaymentResponse;
use Vanilo\Braintree\Messages\BraintreeTransactionRequest;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Contracts\PaymentResponse;

class BraintreePaymentGateway implements PaymentGateway
{
    public const DEFAULT_ID = 'braintree';

    public function __construct(
        private bool $isTest,
        private string $merchantId,
        private string $publicKey,
        private string $privateKey,
    ) {
    }

    public static function getName(): string
    {
        return 'Braintree';
    }

    public function createPaymentRequest(Payment $payment, Address $shippingAddress = null, array $options = []): PaymentRequest
    {
        $request = new BraintreePaymentRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        );

        if (isset($options['submitUrl'])) {
            $request->setSubmitUrl($options['submitUrl']);
        }

        if (isset($options['view'])) {
            $request->setView($options['view']);
        }

        return $request;
    }

    public function processPaymentResponse(Request|Payment $payment, array $options = []): PaymentResponse
    {
        return (new BraintreePaymentResponse(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->process($payment);
    }

    public function createTransaction(Payment $payment, string $nonce): void
    {
        $response = (new BraintreeTransactionRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->create($payment, $nonce);

        if ($response->transaction) {
            $payment->update([
                'remote_id' => $response->transaction->id,
            ]);
        }
    }

    public function isOffline(): bool
    {
        return false;
    }
}
