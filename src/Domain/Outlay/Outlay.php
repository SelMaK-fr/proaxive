<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Outlay;

use Envms\FluentPDO\Literal;

class Outlay
{
    public string $reference = '256985';
    public string $amount = '';
    public ?\DateTime $refund = null;
    public string $payment_method = '';
    public ?string $reference_propal = null;
    public ?string $reference_intervention = null;
    public bool $is_closed;
    public ?string $seller = null;
    public string $products = '';
    public string $status = 'PENDING';
    public int|string $customers_id = 0;
    public bool $is_approved;
    public int|string $users_id = 0;
    public ?string $code_sign = null;
    public Literal $created_at;
    public Literal $updated_at;

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): Outlay
    {
        $this->reference = $reference;
        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): Outlay
    {
        $this->amount = $amount;
        return $this;
    }

    public function getRefund(): ?\DateTime
    {
        return $this->refund;
    }

    public function setRefund(?\DateTime $refund): Outlay
    {
        $this->refund = $refund;
        return $this;
    }

    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): Outlay
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    public function getReferencePropal(): ?string
    {
        return $this->reference_propal;
    }

    public function setReferencePropal(?string $reference_propal): Outlay
    {
        $this->reference_propal = $reference_propal;
        return $this;
    }

    public function getReferenceIntervention(): ?string
    {
        return $this->reference_intervention;
    }

    public function setReferenceIntervention(?string $reference_intervention): Outlay
    {
        $this->reference_intervention = $reference_intervention;
        return $this;
    }

    public function isIsClosed(): bool
    {
        return $this->is_closed;
    }

    public function setIsClosed(bool $is_closed): Outlay
    {
        $this->is_closed = $is_closed;
        return $this;
    }

    public function getSeller(): ?string
    {
        return $this->seller;
    }

    public function setSeller(?string $seller): Outlay
    {
        $this->seller = $seller;
        return $this;
    }

    public function getProducts(): string
    {
        return $this->products;
    }

    public function setProducts(string $products): Outlay
    {
        $this->products = $products;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): Outlay
    {
        $this->status = $status;
        return $this;
    }

    public function getCustomersId(): int
    {
        return $this->customers_id;
    }

    public function setCustomersId(int $customers_id): Outlay
    {
        $this->customers_id = $customers_id;
        return $this;
    }

    public function isIsApproved(): bool
    {
        return $this->is_approved;
    }

    public function setIsApproved(bool $is_approved): Outlay
    {
        $this->is_approved = $is_approved;
        return $this;
    }

    public function getUsersId(): int
    {
        return $this->users_id;
    }

    public function setUsersId(int $users_id): Outlay
    {
        $this->users_id = $users_id;
        return $this;
    }

    public function getCodeSign(): ?string
    {
        return $this->code_sign;
    }

    public function setCodeSign(?string $code_sign): Outlay
    {
        $this->code_sign = $code_sign;
        return $this;
    }

    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): Outlay
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): Outlay
    {
        $this->updated_at = $updated_at;
        return $this;
    }

}