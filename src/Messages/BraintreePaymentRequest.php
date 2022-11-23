<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Illuminate\Support\Facades\View;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Payment\Contracts\PaymentRequest;

class BraintreePaymentRequest implements PaymentRequest
{
    use HasBraintreeInteraction;

    private string $view = 'braintree::_request';

    private string $submitUrl;

    public function getHtmlSnippet(array $options = []): ?string
    {
        return View::make(
            $this->view,
            [
                'clientToken' => $this->gateway->clientToken()->generate(),
                'url' => $this->submitUrl,
            ]
        )->render();
    }

    public function willRedirect(): bool
    {
        // Set this to false or true depending on the nature of the gateway
        // Off-site gateways set this to `true`, on-site ones to `false`
        // Some gateway support both; if your implementation supports
        // both return true/false depending on the particular case
        return true;
    }

    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    public function setSubmitUrl(string $url): self
    {
        $this->submitUrl = $url;

        return $this;
    }
}
