<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Workshop\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class WorkshopRepository extends BaseRepository
{

    protected string $model = 'company';

    public function allWithUser(): array
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select('
            company.id, company.name, company.director, company.mail, company.website, company.phone, company.address, company.city, company.zipcode, company.department,
            COUNT(u.id) as countUsers')
            ->leftJoin('users as u ON u.company_id = company.id')
            ->groupBy('company.id, company.name')
            ->fetchAll();
    }
}