<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Parameter\Interface;

interface ParameterInterface
{
    public function getParam($key): string;
    public function getParams(): mixed;
    public function save($data);
}