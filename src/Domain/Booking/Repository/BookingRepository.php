<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Booking\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class BookingRepository extends BaseRepository
{
    protected string $model = 'booking';

    public function allByBeginAndEnd($beginAt, $endAt)
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select("booking.*")
            ->where('start_date BETWEEN ? AND ?', [$beginAt->format('Y-m-d'), $endAt->format('Y-m-d')])
            ->orderBy('start_date')
            ->fetchAll()
            ;
    }

    public function allByForWeekAndHour($start, $end)
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select("booking.*")
            ->where('start_date >= ? AND start_date < ?', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->orderBy('start_date')
            ->fetchAll()
            ;
    }
}