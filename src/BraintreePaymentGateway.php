<?php

declare(strict_types=1);

namespace Vanilo\Braintree;

use Braintree\Customer;
use Braintree\Result\Error;
use Braintree\Transaction;
use Illuminate\Http\Request;
use Vanilo\Braintree\Exceptions\TransactionCreationException;
use Vanilo\Braintree\Messages\BraintreeClientTokenRequest;
use Vanilo\Braintree\Messages\BraintreeCreateCustomerRequest;
use Vanilo\Braintree\Messages\BraintreeGetCustomerRequest;
use Vanilo\Braintree\Messages\BraintreeGetTransactionRequest;
use Vanilo\Braintree\Messages\BraintreePaymentRequest;
use Vanilo\Braintree\Messages\BraintreePaymentResponse;
use Vanilo\Braintree\Messages\BraintreeRefundTransactionRequest;
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

    public function getClientToken(): string
    {
        return (new BraintreeClientTokenRequest(
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

    public function createTransaction(Payment $payment, ?string $paymentMethodToken, ?string $paymentMethodNonce, ?string $customerId, bool $saveCard = false): Transaction
    {
        $transactionResponse = (new BraintreeTransactionRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->create($payment, $paymentMethodToken, $paymentMethodNonce, $customerId, $saveCard);

        if ($transactionResponse instanceof Error && !$transactionResponse->transaction) {
            throw TransactionCreationException::fromBraintreeError($transactionResponse);
        }

        $payment->update([
            'remote_id' => $transactionResponse->transaction->id,
        ]);

        return $transactionResponse->transaction;
    }

    public function createCustomer(string $firstName, string $lastName, string $email, ?string $phone, ?string $companyName): ?Customer
    {
        return (new BraintreeCreateCustomerRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->create($firstName, $lastName, $email, $phone, $companyName);
    }

    public function getCustomer(string $customerId): ?Customer
    {
        return (new BraintreeGetCustomerRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->get($customerId);
    }

    public function getTransaction(string $transactionId): ?Transaction
    {
        return (new BraintreeGetTransactionRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->get($transactionId);
    }

    public function refundTransaction(string $transactionId, ?double $amount = null)
    {
        return (new BraintreeRefundTransactionRequest(
            $this->isTest,
            $this->merchantId,
            $this->publicKey,
            $this->privateKey
        ))->refund($transactionId, $amount);
    }

    public function isOffline(): bool
    {
        return false;
    }
}
