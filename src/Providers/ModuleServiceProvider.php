<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Braintree\BraintreePaymentGateway;
use Vanilo\Payment\PaymentGateways;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    public function boot()
    {
        parent::boot();

        if ($this->config('gateway.register', true)) {
            PaymentGateways::register(
                $this->config('gateway.id', BraintreePaymentGateway::DEFAULT_ID),
                BraintreePaymentGateway::class
            );
        }

        if ($this->config('bind', true)) {
            $this->app->bind(BraintreePaymentGateway::class, function ($app) {
                return new BraintreePaymentGateway(
                    $this->config('xxx') // @todo replace with real
                );
            });
        }

        $this->publishes([
            $this->getBasePath() . '/' . $this->concord->getConvention()->viewsFolder() =>
            resource_path('views/vendor/braintree'),
            'vanilo-braintree'
        ]);
    }
}
