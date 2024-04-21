<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Statistics\Interface;

interface StatisticsInterface
{

    /**
     * Set field with value
     * Enter field database statistics and value
     * @param string $name
     * @param int|string $value
     * @return void
     */
    public function set(string $name, int|string $value): void;

    /**
     * Get value of field
     * Enter field database statistics
     * @param string $name
     * @return string|int
     */
    public function get(string $name): string|int;

    /**
     * Add (+1) / Enter field database statistics
     * @param string $name
     * @return void
     */
    public function addOne(string $name): void;

    /**
     * Remove (-1) / Enter field database statistics
     * @param string $name
     * @return void
     */
    public function removeOne(string $name): void;

    /**
     * Clear (0/null) field
     * @param string $name
     * @return void
     */
    public function clear(string $name): void;

    /**
     * View all value of statistics
     * @return array
     */
    public function all(): array;

}