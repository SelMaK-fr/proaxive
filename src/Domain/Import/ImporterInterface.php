<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Import;

interface ImporterInterface
{
    public function import(): array;
}