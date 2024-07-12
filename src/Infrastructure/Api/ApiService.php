<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Api;

class ApiService implements ApiInterface
{

    public function all(string $resource, ?int $perPage = 10, ?int $page = 1): array
    {
        // TODO: Implement all() method.
    }

    public function get(string $resource): mixed
    {
        // TODO: Implement get() method.
    }

    public function path(string $resource, ?array $data)
    {
        // TODO: Implement path() method.
    }

    public function post(string $resource, ?array $data)
    {
        // TODO: Implement post() method.
    }

    public function put(string $resource, ?array $data)
    {
        // TODO: Implement put() method.
    }

    public function delete(string $resource, ?array $data)
    {
        // TODO: Implement delete() method.
    }

    private function loginApi(): string
    {

    }
}