<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Database;

use Selmak\Proaxive2\Domain\BaseDTO;

class PhinxDto extends BaseDTO
{
    private ?string $version = null;
    private ?string $migration_name = null;
    private ?\DateTimeImmutable $start_time = null;
    private ?\DateTimeImmutable $end_time = null;
    private ?string $breakpoint = null;

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getMigrationName(): ?string
    {
        return $this->migration_name;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->start_time;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->end_time;
    }

    public function getBreakpoint(): ?string
    {
        return $this->breakpoint;
    }
}
