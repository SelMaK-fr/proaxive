<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Api;

interface ApiInterface
{
    public function all(string $resource, ?int $perPage = 10, ?int $page = 1): array;
    public function get(string $resource): mixed;
    public function path(string $resource, ?array $data);
    public function post(string $resource, ?array $data);
    public function put(string $resource, ?array $data);
    public function delete(string $resource, ?array $data);
}