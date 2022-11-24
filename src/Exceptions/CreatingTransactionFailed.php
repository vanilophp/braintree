<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Exceptions;

use Exception;
use Throwable;

class CreatingTransactionFailed extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
