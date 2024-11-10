<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Equipment;

use Envms\FluentPDO\Literal;
use Selmak\Proaxive2\Domain\BaseDTO;

class Equipment extends BaseDTO
{
    private int $id;
    private string $name;
    private int $customers_id;
    private int $brands_id;
    private int $types_equipments_id;
    private int $operating_systems_id;

    private Literal $created_at;
    private Literal $updated_at;
}