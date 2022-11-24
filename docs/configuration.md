# Configuration

## Dotenv Values

The following `.env` parameters can be set in order to work with this package.

```dotenv
BRAINTREE_IS_TEST=
BRAINTREE_MERCHANT_ID=
BRAINTREE_PUBLIC_KEY=
BRAINTREE_PRIVATE_KEY=
```

## Registration with Payments Module

The module will automatically register the payment gateway with the Vanilo Payments registry by
default. Having that, you can get a gateway instance directly from the Payment registry:

```php
$gateway = \Vanilo\Payment\PaymentGateways::make('braintree');
```

### Registering With Another Name

If you'd like to use another name in the payment registry, it can be done in the module config:

```php
//config/concord.php
return [
    'modules' => [
        //...
        Vanilo\Braintree\Providers\ModuleServiceProvider::class => [
            'gateway' => [
                'id' => 'braintree-gateway'
            ]
        ]
        //...
    ]
];
```

After this you can obtain a gateway instance with the configured name:

```php
\Vanilo\Payment\PaymentGateways::make('braintree-gateway');
```

### Prevent from Auto-registration

If you don't want it to be registered automatically, you can prevent it by changing the module
configuration:

```php
//config/concord.php
return [
    'modules' => [
        //...
        Vanilo\Braintree\Providers\ModuleServiceProvider::class => [
            'gateway' => [
                'register' => false
            ]
        ]
        //...
    ]
];
```

### Manual Registration

If you disable registration and want to register the gateway manually you can do it by using the
Vanilo Payment module's payment gateway registry:

```php
use Vanilo\Braintree\BraintreePaymentGateway;
use Vanilo\Payment\PaymentGateways;

PaymentGateways::register('whatever-name-you-want', BraintreePaymentGateway::class);
```

## Binding With The Laravel Container

By default `BraintreePaymentGateway::class` gets bound to the Laravel DI container, so that you can
obtain a properly autoconfigured instance. Typically, you don't get the instance directly from the
Laravel container (ie. `app()->make(BraintreePaymentGateway::class)`) but from the Vanilo Payment
Gateway registry:

```php
$instance = \Vanilo\Payment\PaymentGateways::make('braintree');
```

The default DI binding happens so that all the configuration parameters are read from the config values
mentioned above. This will work out of the box and will be sufficient for most of the applications.

### Manual Binding

It is possible to prevent the automatic binding and thus configure the Gateway in a custom way in
the module config:

```php
//config/concord.php
return [
    'modules' => [
        Vanilo\Braintree\Providers\ModuleServiceProvider::class => [
            'bind' => false,
```

This can be useful if the Gateway configuration can't be set in the env file, for example when:

- The credentials can be **configured in an Admin interface** instead of `.env`
- Your app has **multiple payment methods** that use Braintree with **different credentials**
- There is a **multi-tenant application**, where each tenant has their own credentials

Setting `vanilo.braintree.bind` to `false` will cause that the class doesn't get bound with the
Laravel DI container automatically. Therefore, you need to do this yourself in your application,
typically in the `AppServiceProvider::boot()` method:

```php
$this->app->bind(BraintreePaymentGateway::class, function ($app) {
    return new BraintreePaymentGateway(
        config('vanilo.braintree.xxx'),  // You can use any source
        config('vanilo.braintree.yyy'),  // other than config()
        config('vanilo.braintree.zzz')  // for passing args
    );
});
```

---

**Next**: [Workflow &raquo;](workflow.md)
