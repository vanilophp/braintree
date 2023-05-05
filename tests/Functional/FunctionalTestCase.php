<?php

declare(strict_types=1);

/**
 * Contains the FunctionalTestCase class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-05-05
 *
 */

namespace Vanilo\Braintree\Tests\Functional;

use Vanilo\Braintree\BraintreePaymentGateway;
use Vanilo\Braintree\Tests\TestCase;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\PaymentGateways;

class FunctionalTestCase extends TestCase
{
    private ?PaymentMethod $paymentMethod = null;


    protected function gateway(): BraintreePaymentGateway
    {
        return PaymentGateways::make(BraintreePaymentGateway::DEFAULT_ID);
    }

    protected function paymentMethod(): PaymentMethod
    {
        if (null === $this->paymentMethod) {
            $this->paymentMethod = PaymentMethod::create([
                'name' => 'Braintree',
                'gateway' => BraintreePaymentGateway::DEFAULT_ID,
            ]);
        }

        return $this->paymentMethod;
    }
}
