<?php

declare(strict_types=1);

/**
 * Contains the TransactionTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-05-05
 *
 */

namespace Vanilo\Braintree\Tests\Functional;

use Braintree\Transaction;
use Vanilo\Braintree\Messages\BraintreePaymentRequest;
use Vanilo\Braintree\Tests\Dummies\Order;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Factories\PaymentFactory;

class TransactionTest extends FunctionalTestCase
{
    /** @ test */
    public function it_can_create_a_transaction_from_a_payable()
    {
        $order = Order::create(['amount' => 21.99, 'currency' => 'EUR']);
        $payment = PaymentFactory::createFromPayable($order, $this->paymentMethod());
        /** @var BraintreePaymentRequest $request */
        $request = $this->gateway()->createPaymentRequest($payment);
        $transaction = $this->gateway()->createTransaction($payment, null, $request->getClientToken(), null);

        $this->assertInstanceOf(PaymentRequest::class, $request);
        $this->assertInstanceOf(Transaction::class, $transaction);
    }
}
