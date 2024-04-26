<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Intervention;

use Envms\FluentPDO\Literal;

class Intervention
{
    /* @var string */
    public string $name;
    /* @var string */
    public string $sort = 'Dépannage';
    /* @var string|null */
    public ?string $description = '';
    /* @var string|null */
    public ?string $before_breakdown = '';
    /* @var int|string */
    public int|string $company_id = 0;
    /* @var int|string */
    public int|string $customers_id = 0;
    /* @var int|string */
    public int|string $users_id = 0;
    /* @var string */
    public string $ref_number;
    /* @var string */
    public string $ref_for_link;
    /* @var string */
    public string $state = 'PENDING';
    public Literal $created_at;
    public Literal $updated_at;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Intervention
    {
        $this->name = $name;
        return $this;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): Intervention
    {
        $this->sort = $sort;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Intervention
    {
        $this->description = $description;
        return $this;
    }

    public function getBeforeBreakdown(): ?string
    {
        return $this->before_breakdown;
    }

    public function setBeforeBreakdown(?string $before_breakdown): Intervention
    {
        $this->before_breakdown = $before_breakdown;
        return $this;
    }

    public function getCompanyId(): int|string
    {
        return $this->company_id;
    }

    public function setCompanyId(int|string $company_id): Intervention
    {
        $this->company_id = (int)$company_id;
        return $this;
    }

    public function getCustomersId(): int|string
    {
        return $this->customers_id;
    }

    public function setCustomersId(int|string $customers_id): Intervention
    {
        $this->customers_id = (int)$customers_id;
        return $this;
    }

    public function getUsersId(): int|string
    {
        return $this->users_id;
    }

    public function setUsersId(int|string $users_id): Intervention
    {
        $this->users_id = (int)$users_id;
        return $this;
    }

    public function getRefNumber(): string
    {
        return $this->ref_number;
    }

    public function setRefNumber(string $ref_number): Intervention
    {
        $this->ref_number = $ref_number;
        return $this;
    }

    public function getRefForLink(): string
    {
        return $this->ref_for_link;
    }

    public function setRefForLink(string $ref_for_link): Intervention
    {
        $this->ref_for_link = $ref_for_link;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): Intervention
    {
        $this->state = $state;
        return $this;
    }

    public function getCreatedAt(): Literal
    {
        return $this->created_at;
    }

    public function setCreatedAt(Literal $created_at): Intervention
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): Literal
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(Literal $updated_at): Intervention
    {
        $this->updated_at = $updated_at;
        return $this;
    }


}