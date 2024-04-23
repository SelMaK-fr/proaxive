<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Yaml;

interface ExtractDataYamlInterface
{
    public function get(string $section, string $key = '');
}