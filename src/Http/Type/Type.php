<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Envms\FluentPDO\Query;

abstract class Type
{
    public function __construct(protected readonly Query|array $query){}
}