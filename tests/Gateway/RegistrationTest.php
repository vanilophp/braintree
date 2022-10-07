<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Tests\Gateway;

use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Braintree\BraintreePaymentGateway;
use Vanilo\Braintree\Tests\TestCase;

class RegistrationTest extends TestCase
{
    /** @test */
    public function the_gateway_is_registered_out_of_the_box_with_defaults()
    {
        $this->assertCount(2, PaymentGateways::ids());
        $this->assertContains(BraintreePaymentGateway::DEFAULT_ID, PaymentGateways::ids());
    }

    /** @test */
    public function the_gateway_can_be_instantiated()
    {
        $gateway = PaymentGateways::make('braintree');

        $this->assertInstanceOf(PaymentGateway::class, $gateway);
        $this->assertInstanceOf(BraintreePaymentGateway::class, $gateway);
    }
}
