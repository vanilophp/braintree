<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Transaction;
use Illuminate\Support\Arr;
use Konekt\Enum\Enum;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Braintree\Models\BraintreeTransactionStatus;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Payment\Models\PaymentStatusProxy;

class BraintreePaymentResponse implements PaymentResponse
{
    use HasBraintreeInteraction;

    private string $paymentId;

    private ?float $amountPaid;

    private BraintreeTransactionStatus $nativeStatus;

    private ?PaymentStatus $status = null;

    private string $message;

    private bool $wasSuccessful = false;

    private ?string $transactionId;

    public function process(Transaction $transaction): self
    {
        $this->nativeStatus = BraintreeTransactionStatus::create($transaction->status);
        $this->paymentId = Arr::get($transaction->customFields, 'payment_id');
        $this->transactionId = $transaction->id;
        $this->message = $transaction->processorResponseText;
        $this->amountPaid = (float) $transaction->amount;

        $this->resolveStatus();

        return $this;
    }

    public function wasSuccessful(): bool
    {
        return $this->wasSuccessful;
    }

    public function getMessage(): string
    {
        return $this->message ?? $this->nativeStatus->label();
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function getNativeStatus(): Enum
    {
        return $this->nativeStatus;
    }

    public function toArray()
    {
        return [
            'native_status' => $this->getNativeStatus()->value(),
            'status' => $this->getStatus()->value(),
            'wasSuccessfull' => $this->wasSuccessful(),
            'amount_paid' => $this->getAmountPaid(),
            'payment_id' => $this->getPaymentId(),
            'braintree_id' => $this->getTransactionId(),
            'message' => $this->getMessage(),
        ];
    }

    private function resolveStatus()
    {
        switch ($this->getNativeStatus()->value()) {
            case BraintreeTransactionStatus::AUTHORIZING:
                $this->status = PaymentStatusProxy::PENDING();
                $this->wasSuccessful = true;
                break;
            case BraintreeTransactionStatus::AUTHORIZED:
            case BraintreeTransactionStatus::SUBMITTED_FOR_SETTLEMENT:
            case BraintreeTransactionStatus::SETTLING:
            case BraintreeTransactionStatus::SETTLEMENT_PENDING:
                $this->status = PaymentStatusProxy::AUTHORIZED();
                $this->wasSuccessful = true;
                break;
            case BraintreeTransactionStatus::AUTHORIZATION_EXPIRED:
            case BraintreeTransactionStatus::SETTLEMENT_DECLINED:
            case BraintreeTransactionStatus::FAILED:
            case BraintreeTransactionStatus::GATEWAY_REJECTED:
            case BraintreeTransactionStatus::PROCESSOR_DECLINED:
                $this->status = PaymentStatusProxy::DECLINED();
                $this->wasSuccessful = false;
                break;
            case BraintreeTransactionStatus::SETTLED:
                $this->status = PaymentStatusProxy::PAID();
                $this->wasSuccessful = true;
                break;
            case BraintreeTransactionStatus::VOIDED:
                $this->status = PaymentStatusProxy::DECLINED();
                $this->wasSuccessful = true;
                break;
            default:
                $this->status = PaymentStatusProxy::DECLINED();
                $this->wasSuccessful = false;
        }
    }
}
