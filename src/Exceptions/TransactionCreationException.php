<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Exceptions;

use Braintree\Result\Error;
use RuntimeException;

class TransactionCreationException extends RuntimeException
{
    public static function fromBraintreeError(Error $error)
    {
        return new self($error->message);
    }
}
