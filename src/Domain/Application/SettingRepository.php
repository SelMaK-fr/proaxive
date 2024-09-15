<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Application;

use Selmak\Proaxive2\Domain\BaseRepository;

class SettingRepository extends BaseRepository
{
    protected string $model = 'settings';
}