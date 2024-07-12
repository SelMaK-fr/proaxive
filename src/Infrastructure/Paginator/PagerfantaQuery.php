<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Paginator;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use Pagerfanta\Adapter\AdapterInterface;

class PagerfantaQuery implements AdapterInterface
{
    /**
     * @param Select $query
     */
    public function __construct(private readonly Select $query){}

    public function getNbResults(): int
    {
        return $this->query->count();
    }

    public function getSlice(int $offset, int $length): iterable
    {
        return $this->query->offset($offset)->limit($length);
    }
}