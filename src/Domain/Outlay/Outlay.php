<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Outlay;

class Outlay
{
    public string $reference;
    public float $amount;
    public ?\DateTime $refund = null;
    public string $payment_method;
    public ?string $reference_propal = null;
    public bool $is_closed = false;
    public bool $is_signed = false;
    public ?string $seller = null;
    public string $products;
    public string $status = 'PENDING';
    public int $customers_id;
    public bool $is_approved = false;
    public int $users_id;
    public ?string $code_sign = null;
}