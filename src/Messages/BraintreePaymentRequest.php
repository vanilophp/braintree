<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Illuminate\Support\Facades\View;
use Vanilo\Payment\Contracts\PaymentRequest;

class BraintreePaymentRequest implements PaymentRequest
{
    private string $view = 'braintree::_request';

    private string $approveUrl;

    public function __construct()
    {
        // accept arguments, initialize
    }

    public function getHtmlSnippet(array $options = []): ?string
    {
        return View::make(
            $this->view,
            [
                // Inject needed variables here
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
}
