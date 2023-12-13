<?php
declare(strict_types=1);
namespace App;

use Envms\FluentPDO\Query;

abstract class Type
{
    public function __construct(protected readonly Query|array $query){}
}