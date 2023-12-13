<?php
declare(strict_types=1);
namespace App\Repository;

use App\BaseRepository;

class TaskRepository extends BaseRepository
{
    protected string $model = 'tasks';
}