<?php

declare(strict_types=1);

/**
 * Contains the TransactionType class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-04-13
 *
 */

namespace Vanilo\Braintree\Models;

use Braintree\Transaction;
use Konekt\Enum\Enum;

/**
 * @method static SALE()
 * @method static CREDIT()
 *
 * @property-read bool $is_credit
 * @property-read bool $is_sale
 */
class TransactionType extends Enum
{
    public const SALE = Transaction::SALE;
    public const CREDIT = Transaction::CREDIT;

    public function signum(): int
    {
        return $this->is_credit ? -1 : 1;
    }
}
