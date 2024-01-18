<?php
declare(strict_types=1);
namespace App\Repository;

use App\BaseRepository;

class AccountRepository extends BaseRepository
{
    protected string $model = 'users';
}