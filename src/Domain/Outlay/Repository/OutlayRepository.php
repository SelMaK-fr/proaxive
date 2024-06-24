<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Outlay\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class OutlayRepository extends BaseRepository
{
    protected string $model = 'outlay';

    public function allWithCustomer()
    {
        return $this->makeQueryDefault()
            ->select('outlay.*, c.id c_id, c.fullname c_fullname')
            ->leftJoin('customers as c ON c.id = outlay.customers_id')
            ->fetchAll();
    }
}