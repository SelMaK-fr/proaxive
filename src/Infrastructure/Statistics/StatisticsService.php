<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Statistics;

use Envms\FluentPDO\Literal;
use Envms\FluentPDO\Query;
use Selmak\Proaxive2\Infrastructure\Statistics\Interface\StatisticsInterface;
use Slim\App;

class StatisticsService implements StatisticsInterface
{

    const STAT_MODEL = "statistics";
    public function __construct(private readonly App $app){}

    public function set(string $name, int|string $value): void
    {
        /* @var $query Query */
        $query = $this->app->getContainer()->get(Query::class);
        $query->update(self::STAT_MODEL, [$name => $value, 1]);
    }

    public function get(string $name): string|int
    {
        /* @var $query Query */
        $query = $this->app->getContainer()->get(Query::class);
        $field = $query->from(self::STAT_MODEL)
            ->asObject()
            ->where("id = ?", [1])
            ->fetch()
        ;
        return $field->$name;
    }

    public function addOne(string $name): void
    {
        /* @var $query Query */
        $query = $this->app->getContainer()->get(Query::class);
        $query->update(self::STAT_MODEL)
            ->set($name, new Literal($name . ' + 1'))
            ->where('id = ?', 1)
            ->execute();
    }

    public function removeOne(string $name): void
    {
        /* @var $query Query */
        $query = $this->app->getContainer()->get(Query::class);
        $query->update(self::STAT_MODEL)
            ->set($name, new Literal($name . ' - 1'))
            ->where('id = ?', 1)
            ->execute();
    }

    public function clear(string $name): void
    {
        /* @var $query Query */
        $query = $this->app->getContainer()->get(Query::class);
        $query->update(self::STAT_MODEL)
            ->set($name, 0)
            ->where('id = ?', 1)
            ->execute();
    }

    public function all(): array
    {
        // TODO: Implement all() method.
    }
}