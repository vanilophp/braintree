<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Models;

use Konekt\Enum\Enum;

class BraintreeStatus extends Enum
{
    // Feel free to rename this class to whatever it
    // represents at the specific payment gateway
    // but please keep the "Braintree" class prefix.
    // Examples:
    // - "BraintreeTransactionStatus",
    // - "BraintreeOrderStatus",
    // - "BraintreePaymentStatus",
    // - "BraintreeInvoiceStatus",
    // - etc.

    // Add constants as possible values.
    // See: https://vanilo.io/docs/2.x/enums

    // The values below are just examples, take the actual
    // list of possible values from the Braintree documentation:
    public const CREATED = 'created';
    public const PENDING_OK = 'pending_ok';
    public const INVALID_DATA = 'invalid_data';
    public const FRAUD_CHECK = 'fraud_check';
    public const AUTH_OK = 'auth_ok';
    public const CAPTURED = 'captured';
    public const FRAUD_DETECTED = 'fraud_detected';
}
