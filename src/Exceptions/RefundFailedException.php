<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Exceptions;

use Braintree\Result\Error;
use RuntimeException;
use Throwable;

class RefundFailedException extends RuntimeException
{
    public static function fromException(Throwable $exception): self
    {
        return new self($exception->getMessage());
    }

    public static function fromBraintreeError(Error $error): self
    {
        return new self($error->message);
    }
}
