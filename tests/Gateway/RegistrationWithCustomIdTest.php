<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Tests\Gateway;

use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Braintree\BraintreePaymentGateway;
use Vanilo\Braintree\Tests\TestCase;

class RegistrationWithCustomIdTest extends TestCase
{
    protected function setUp(): void
    {
        PaymentGateways::reset();
        parent::setUp();
    }

    /** @test */
    public function the_gateway_id_can_be_changed_from_within_the_configuration()
    {
        $this->assertCount(2, PaymentGateways::ids());
        $this->assertContains('alternative_gw_name', PaymentGateways::ids());
    }

    /** @test */
    public function the_gateway_can_be_instantiated()
    {
        $payPalGateway = PaymentGateways::make('alternative_gw_name');

        $this->assertInstanceOf(PaymentGateway::class, $payPalGateway);
        $this->assertInstanceOf(BraintreePaymentGateway::class, $payPalGateway);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        config(['vanilo.braintree.gateway.id' => 'alternative_gw_name']);
    }
}
