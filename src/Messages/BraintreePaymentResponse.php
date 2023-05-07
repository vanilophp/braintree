<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Messages;

use Braintree\Transaction;
use Illuminate\Support\Arr;
use Konekt\Enum\Enum;
use Vanilo\Braintree\Concerns\HasBraintreeInteraction;
use Vanilo\Braintree\Exceptions\UnsupportedOperationException;
use Vanilo\Braintree\Models\BraintreeTransactionStatus;
use Vanilo\Braintree\Models\TransactionType;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Payment\Models\PaymentStatusProxy;

class BraintreePaymentResponse implements PaymentResponse
{
    use HasBraintreeInteraction;

    private string $paymentId;

    private ?float $amount;

    private BraintreeTransactionStatus $nativeStatus;

    private TransactionType $transactionType;

    private ?PaymentStatus $status = null;

    private string $message;

    private ?string $subType = null;

    private bool $wasSuccessful = false;

    private ?string $transactionId;

    /** @var null|array{id: string, amount: float} */
    private ?array $refundedTransaction = null;

    public function process(Transaction $transaction): self
    {
        $this->nativeStatus = BraintreeTransactionStatus::create($transaction->status);
        $this->paymentId = $this->resolvePaymentId($transaction);
        $this->transactionId = $transaction->id;
        $this->message = $transaction->processorResponseText;
        $this->transactionType = TransactionType::create($transaction->type);
        $this->amount = is_null($transaction->amount) ? null : floatval($transaction->amount);
        $this->subType = $transaction->paymentInstrumentType;
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
        return $this->transactionType->signum() * $this->amount;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function getSubType(): ?string
    {
        return $this->subType;
    }

    public function getNativeStatus(): Enum
    {
        return $this->nativeStatus;
    }

    /**
     * @return array{native_status: string, status: string, was_successful: bool, amount_paid: ?float, payment_id: string, braintree_id: ?string, message: string}
     */
    public function toArray()
    {
        return [
            'native_status' => $this->getNativeStatus()->value(),
            'status' => $this->getStatus()->value(),
            /** @deprecated remove with v2.x */
            'wasSuccessfull' => $this->wasSuccessful(),
            'was_successful' => $this->wasSuccessful(),
            'amount_paid' => $this->getAmountPaid(),
            'payment_id' => $this->getPaymentId(),
            'braintree_id' => $this->getTransactionId(),
            'message' => $this->getMessage(),
        ];
    }

    private function resolvePaymentId(Transaction $transaction): string
    {
        if (null !== $paymentId = Arr::get($transaction->customFields, 'payment_id')) {
            return $paymentId;
        } elseif (null !== $transaction->refundedTransactionId) {
            $refundedTransaction = $this->gateway->transaction()->find($transaction->refundedTransactionId);
            $this->refundedTransaction = [
                'id' => $refundedTransaction->id,
                'amount' => $refundedTransaction->amount,
            ];

            return $this->resolvePaymentId($refundedTransaction);
        }

        throw new UnsupportedOperationException($transaction->__toString());
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
                if ($this->transactionType->is_sale) {
                    $this->status = PaymentStatusProxy::AUTHORIZED();
                } else { // is a credit transaction
                    if (null !== $this->refundedTransaction) {
                        if ($this->refundedTransaction['amount'] > $this->amount) {
                            $this->status = PaymentStatusProxy::PARTIALLY_REFUNDED();
                        } else {
                            $this->status = PaymentStatusProxy::REFUNDED();
                        }
                    } else { // This is a guess:
                        $this->status = PaymentStatusProxy::PARTIALLY_REFUNDED();
                    }
                }
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
            case BraintreeTransactionStatus::VOIDED:
                $this->status = PaymentStatusProxy::CANCELLED();
                $this->wasSuccessful = false;
                break;
            case BraintreeTransactionStatus::SETTLED:
                $this->status = PaymentStatusProxy::PAID();
                $this->wasSuccessful = true;
                break;
            default:
                $this->status = PaymentStatusProxy::DECLINED();
                $this->wasSuccessful = false;
        }
    }
}
