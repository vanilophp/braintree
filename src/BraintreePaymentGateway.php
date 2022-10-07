<?php

declare(strict_types=1);

namespace Vanilo\Braintree;

use Illuminate\Http\Request;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Contracts\PaymentResponse;

class BraintreePaymentGateway implements PaymentGateway
{
    public const DEFAULT_ID = 'braintree';

    public static function getName(): string
    {
        return 'Braintree';
    }

    public function createPaymentRequest(Payment $payment, Address $shippingAddress = null, array $options = []): PaymentRequest
    {
        // @todo implement
    }

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse
    {
        // @todo implement
    }

    public function isOffline(): bool
    {
        return false;
    }
}
