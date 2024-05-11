<?php

namespace Selmak\Proaxive2\Infrastructure\Security;

interface SerialNumberFormatterInterface
{
    public function getPlaceholders(string $format): array;

    public function generateSerialNumber(): string;
}