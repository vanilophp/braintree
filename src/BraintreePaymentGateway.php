<?php

declare(strict_types=1);

namespace Vanilo\Braintree;

use Braintree\Result\Error;
use Braintree\Transaction;
use Illuminate\Http\Request;
use Vanilo\Braintree\Exceptions\CreatingTransactionFailed;
use Vanilo\Braintree\Messages\BraintreeClientCodeRequest;
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

    public function getClientCode(): string
    {
        return (new BraintreeClientCodeRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->get();
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

    public function processPaymentResponse(Request|Transaction $request, array $options = []): PaymentResponse
    {
        return (new BraintreePaymentResponse(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->process($request);
    }

    public function createTransaction(Payment $payment, string $nonce): Transaction
    {
        $transactionResponse = (new BraintreeTransactionRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->create($payment, $nonce);

        if ($transactionResponse instanceof Error && !$transactionResponse->transaction) {
            throw new CreatingTransactionFailed($transactionResponse->message);
        }

        $payment->update([
            'remote_id' => $transactionResponse->transaction->id,
        ]);

        return $transactionResponse->transaction;
    }

    public function isOffline(): bool
    {
        return false;
    }
}
