<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\EDocument\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class EDocumentRepository extends BaseRepository
{
    protected string $model = 'edocuments';

    public function allWithIntervention(int $id)
    {
        return $this->makeQueryDefault()
            ->select('edocuments.*, c.id c_id, i.name i_name, i.ref_number i_ref_number')
            ->leftJoin('customers as c ON c.id = edocuments.customers_id')
            ->leftJoin('interventions as i ON i.id = edocuments.interventions_id')
            ->where('edocuments.customers_id = ?', [$id])
            ->fetchAll()
            ;
    }

    public function findOneByReference($reference)
    {
        return $this->findBy('reference', $reference)->fetch();
    }

    public function allWithCustomerAndIntervention()
    {
        return $this->makeQueryDefault()
            ->select('edocuments.*, c.id c_id, c.fullname c_fullname, i.name i_name, i.ref_number i_ref_number')
            ->leftJoin('customers as c ON c.id = edocuments.customers_id')
            ->leftJoin('interventions as i ON i.id = edocuments.interventions_id')
            ->fetchAll()
            ;
    }

}