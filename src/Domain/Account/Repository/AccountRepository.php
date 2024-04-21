<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Account\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class AccountRepository extends BaseRepository
{
    protected string $model = 'users';
}