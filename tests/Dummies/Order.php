<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\Payable;

class Order extends Model implements Payable
{
    protected $fillable = ['amount', 'currency'];

    public function getPayableId(): string
    {
        return (string) $this->id;
    }

    public function getPayableType(): string
    {
        return self::class;
    }

    public function getTitle(): string
    {
        return 'Order #' . $this->id;
    }

    public function getAmount(): float
    {
        return floatval($this->amount);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBillpayer(): ?Billpayer
    {
        return null;
    }
}
