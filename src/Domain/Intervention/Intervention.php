<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Intervention;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class Intervention extends BaseDTO
{
    private ?int $id = null;
    /* @var string */
    private string $name;
    /* @var string */
    private string $sort = 'Dépannage';
    /* @var string|null */
    private ?string $description;
    /* @var string|null */
    private ?string $before_breakdown;
    private ?string $way_type = null;
    private int $way_steps = 1;
    private ?string $a_priority = 'AVERAGE';
    /* @var int|string */
    private int|string $company_id;
    /* @var int */
    private int $customers_id;
    /* @var int|null */
    private ?int $equipments_id = null;
    /* @var int */
    private int $users_id;
    /* @var string */
    private string $ref_number;
    /* @var string */
    private string $ref_for_link;
    /* @var string */
    private string $state = 'VALIDATED';
    private string $customer_name;
    private ?string $equipment_name = null;
    private int $status_id = 1;
    private ?int $total_time = null;
    private Literal $created_at;
    private Literal $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Intervention
    {
        $this->id = $id;
        return $this;
    }

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

    public function getEquipmentsId(): int|null
    {
        return $this->equipments_id;
    }

    public function setEquipmentsId(int|null $equipments_id): ?Intervention
    {
        $this->equipments_id = $equipments_id;
        return $this;
    }

    public function getWayType(): ?string
    {
        return $this->way_type;
    }

    public function setWayType(?string $way_type): Intervention
    {
        $this->way_type = $way_type;
        return $this;
    }

    public function getWaySteps(): ?int
    {
        return $this->way_steps;
    }

    public function setWaySteps(?int $way_steps): Intervention
    {
        $this->way_steps = $way_steps;
        return $this;
    }

    public function getAPriority(): ?string
    {
        return $this->a_priority;
    }

    public function setAPriority(?string $a_priority): Intervention
    {
        $this->a_priority = $a_priority;
        return $this;
    }

    public function getCustomerName(): string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): Intervention
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    public function getEquipmentName(): ?string
    {
        return $this->equipment_name;
    }

    public function setEquipmentName(?string $equipment_name): Intervention
    {
        $this->equipment_name = $equipment_name;
        return $this;
    }

    public function getTotalTime(): int
    {
        return $this->total_time;
    }

    public function setTotalTime(int $total_time): Intervention
    {
        $this->total_time = $total_time;
        return $this;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id): Intervention
    {
        $this->status_id = $status_id;
        return $this;
    }

}