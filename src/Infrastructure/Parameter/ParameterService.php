<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Parameter;

use Selmak\Proaxive2\Infrastructure\Parameter\Interface\ParameterInterface;

class ParameterService implements ParameterInterface
{
    public function __construct(private readonly string $files){}

    public function getParam($key): string
    {
        $readJson = file_get_contents($this->files);
        $param = json_decode($readJson, false);
        return $param->$key;
    }

    public function getParams(): mixed
    {
        $readJson = file_get_contents($this->files);
        return json_decode($readJson, true);
    }

    public function save($data): false|int
    {
        $file = fopen($this->files, 'w');
        return fwrite($file, json_encode($data));
    }
}