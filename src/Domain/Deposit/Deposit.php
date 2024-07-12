<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Deposit;

use Selmak\Proaxive2\Domain\BaseDTO;

class Deposit extends BaseDTO
{
    private string|int $reference;
    private string $deposit_date;
    private string|null $about_state = null;
    private string $customer_name;
    private string $equipment_name;
    private string $intervention_number;
    private string|null $equipment_accessories;
    private int $equipment_state;
    private int $equipments_id;
    private int $interventions_id;
    private int $customers_id;
    private int $company_id;
    private int|null $is_signed;

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string|int $reference): Deposit
    {
        $this->reference = $reference;
        return $this;
    }

    public function getDepositDate(): string|int
    {
        return $this->deposit_date;
    }

    public function setDepositDate(string $deposit_date): Deposit
    {
        $this->deposit_date = $deposit_date;
        return $this;
    }

    public function getAboutState(): ?string
    {
        return $this->about_state;
    }

    public function setAboutState(?string $about_state): Deposit
    {
        $this->about_state = $about_state;
        return $this;
    }

    public function getCustomerName(): string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): Deposit
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    public function getEquipmentName(): string
    {
        return $this->equipment_name;
    }

    public function setEquipmentName(string $equipment_name): Deposit
    {
        $this->equipment_name = $equipment_name;
        return $this;
    }

    public function getInterventionNumber(): string
    {
        return $this->intervention_number;
    }

    public function setInterventionNumber(string $intervention_number): Deposit
    {
        $this->intervention_number = $intervention_number;
        return $this;
    }

    public function getEquipmentAccessories(): ?string
    {
        return $this->equipment_accessories;
    }

    public function setEquipmentAccessories(?string $equipment_accessories): Deposit
    {
        $this->equipment_accessories = $equipment_accessories;
        return $this;
    }

    public function getEquipmentState(): int
    {
        return $this->equipment_state;
    }

    public function setEquipmentState(int $equipment_state): Deposit
    {
        $this->equipment_state = $equipment_state;
        return $this;
    }

    public function getEquipmentsId(): int
    {
        return $this->equipments_id;
    }

    public function setEquipmentsId(int $equipments_id): Deposit
    {
        $this->equipments_id = $equipments_id;
        return $this;
    }

    public function getInterventionsId(): int
    {
        return $this->interventions_id;
    }

    public function setInterventionsId(int $interventions_id): Deposit
    {
        $this->interventions_id = $interventions_id;
        return $this;
    }

    public function getCustomersId(): int
    {
        return $this->customers_id;
    }

    public function setCustomersId(int $customers_id): Deposit
    {
        $this->customers_id = $customers_id;
        return $this;
    }

    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function setCompanyId(int $company_id): Deposit
    {
        $this->company_id = $company_id;
        return $this;
    }

    public function getIsSigned(): ?int
    {
        return $this->is_signed;
    }

    public function setIsSigned(?int $is_signed): Deposit
    {
        $this->is_signed = $is_signed;
        return $this;
    }

}