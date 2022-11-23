<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Models;

use Konekt\Enum\Enum;

class BraintreeTransactionStatus extends Enum
{
    public const AUTHORIZATION_EXPIRED = 'authorization_expired';
    public const AUTHORIZED = 'authorized';
    public const AUTHORIZING = 'authorizing';
    public const SETTLEMENT_PENDING = 'settlement_pending';
    public const SETTLEMENT_DECLINED = 'settlement_declined';
    public const FAILED = 'failed';
    public const GATEWAY_REJECTED = 'gateway_rejected';
    public const PROCESSOR_DECLINED = 'processor_declined';
    public const SETTLED = 'settled';
    public const SETTLING = 'settling';
    public const SUBMITTED_FOR_SETTLEMENT = 'submitted_for_settlement';
    public const VOIDED = 'voided';
}
