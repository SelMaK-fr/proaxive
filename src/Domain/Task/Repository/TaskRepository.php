<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Task\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class TaskRepository extends BaseRepository
{
    protected string $model = 'tasks';
}