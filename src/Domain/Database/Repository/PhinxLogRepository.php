<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Database\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class PhinxLogRepository extends BaseRepository
{
    protected string $model = 'phinxlog';

    public function getLastUpdate()
    {
        return $this->makeQueryDefault()->orderBy('version DESC')->limit(1)->fetch();
    }
}