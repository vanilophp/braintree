<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Models;

use Braintree\Transaction;
use Konekt\Enum\Enum;

class BraintreeTransactionStatus extends Enum
{
    public const AUTHORIZATION_EXPIRED = Transaction::AUTHORIZATION_EXPIRED;
    public const AUTHORIZED = Transaction::AUTHORIZED;
    public const AUTHORIZING = Transaction::AUTHORIZING;
    public const SETTLEMENT_PENDING = Transaction::SETTLEMENT_PENDING;
    public const SETTLEMENT_DECLINED = Transaction::SETTLEMENT_DECLINED;
    public const FAILED = Transaction::FAILED;
    public const GATEWAY_REJECTED = Transaction::GATEWAY_REJECTED;
    public const PROCESSOR_DECLINED = Transaction::PROCESSOR_DECLINED;
    public const SETTLED = Transaction::SETTLED;
    public const SETTLING = Transaction::SETTLING;
    public const SUBMITTED_FOR_SETTLEMENT = Transaction::SUBMITTED_FOR_SETTLEMENT;
    public const VOIDED = Transaction::VOIDED;
}
