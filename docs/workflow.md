# Braintree Payment Workflow

The typical Vanilo Payments workflow with Braintree
consists of the following steps:

1. Create an **Order** (or any
   ["Payable"](https://vanilo.io/docs/2.x/payments#payables))
2. Obtain the **payment method** from the checkout<sup>*</sup>
3. Get the appropriate **gateway instance** associated with the payment
   method
4. Generate a **payment request** using the gateway which bascially creates a `clientToken` via Braintee API
5. Inject the **HTML snippet** on the checkout/thankyou page
6. The HTML snippet will render out the [Braintree Drop In](https://developer.paypal.com/braintree/docs/start/drop-in) containing the `clientToken`
7. Fill the drop in and submit
8. The gateway creates the transaction in Braintree and return a `PaymentResponse`

## Obtain Gateway Instance

Once you have an order (or any other payable), then the starting point
of payment operations is obtaining a gateway instance:

```php
$gateway = \Vanilo\Payment\PaymentGateways::make('braintree');
// Vanilo\Braintree\BraintreePaymentGateway
```

The gateway provides you two essential methods:

- `createPaymentRequest` - Gets the `clientToken` from Braintree and provides the HTML snippet which can be injected to render the Drop-In
- `createTransaction` - Creates the transaction from an order (payable)
- `processPaymentResponse` - Processes the `Transaction` object returned by `createTransaction` function

## Starting Online Payments

**Controller:**

```php
use Vanilo\Framework\Models\Order;
use Vanilo\Payment\Factories\PaymentFactory;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\PaymentGateways;

class OrderController
{
    public function submit(Request $request)
    {
        $order = Order::createFrom($request);
        $paymentMethod = PaymentMethod::find($request->get('paymentMethod'));
        $payment = PaymentFactory::createFromPayable($order, $paymentMethod);
        $gateway = PaymentGateways::make('braintree');
        $paymentRequest = $gateway->createPaymentRequest($payment, options: ['submitUrl' => route('payment.braintree.submit', $payment->hash)]);
 
        return view('order.confirmation', [
            'order' => $order,
            'paymentRequest' => $paymentRequest
        ]);
    }
}
```

**Blade Template:**

```blade
{!! $paymentRequest->getHtmlSnippet(); !!}
```

The generated HTML snippet will contain a prepared, Braintree Drop-In.

### Payment Request Options

The gateway's `createPaymentRequest` method accepts additional
parameters that can be used to customize the generated request.

The signature of the method is the following:

```php
public function createPaymentRequest(
    Payment $payment,
    Address $shippingAddress = null,
    array $options = []
    ): PaymentRequest
```

1. The first parameter is the `$payment`. Every attempt to settle a
   payable is a new `Payment` record.
2. The second one is the `$shippingAddress` in case it differs from
   billing address. It can be left NULL.
3. The third parameters is an array with possible `$options`.

You can pass the following values in the `$options` array:

| Array Key | Example                                             | Description                                                                                                                                        |
|:----------|:----------------------------------------------------|:---------------------------------------------------------------------------------------------------------------------------------------------------|
| `view`    | `vanilo.braintree._form`                            | By default it's `braintree::_request` You can use a custom blade view to render the HTML snippet instead of the default one this library provides. |
| `submitUrl`     | `route('payment.braintree.submit', $payment->hash)` | You must set this url which the action where the Drop-In will be submitted.                                                                        |

**Example**:

```php
$options = [
    'view' => 'vanilo.braintree._form',
    'submitUrl'  => route('payment.braintree.submit', $payment->hash),
];
$gateway->createPaymentRequest($payment, null, $options);
```

#### Customizing The Generated HTML

Apart from passing the `view` option to the `createPaymentRequest` (see
above), there's an even more simple way: Laravel lets you
[override the views from vendor packages](https://laravel.com/docs/8.x/packages#overriding-package-views)
like this.

Simply put, if you create the
`resources/views/vendor/braintree/_request.blade.php` file in your
application, then this blade view will be used instead of the one
supplied by the package.

To get the default view from the package and start customizing it, use
this command:

```bash
php artisan vendor:publish --tag=vanilo-braintree
```

This will copy the default blade view used to render the HTML form into
the `resources/views/vendor/braintree/` folder of your application. After
that, the `getHtmlSnippet()` method will use the copied blade template
to render the HTML snippet for Braintree payment requests.

## Confirm And Return URLs

Since Braintree doesn't have webhook for the `Transaction`s basically the entire flow is a sync call of endpoints.
Therefore there is not return/cancel urls involved.

---

**Next**: [Examples &raquo;](examples.md)
