<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Customer\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class CustomerRepository extends BaseRepository
{

    protected string $model = 'customers';

    public function search(string $key): \Envms\FluentPDO\Queries\Select
    {
        return $this->makeQueryDefault()->where('fullname LIKE ?', ["%".$key."%"])->orderBy('fullname');

    }

    public function getByClientID($login)
    {
        return $this->makeQueryDefault()->where('mail = ? or login_id = ?', [$login,$login])->fetch();
    }

    public function statsChartsProfil(int $id)
    {
        return $this->getQuery()
            ->from('customers')
            ->leftJoin('interventions ON interventions.customers_id = customers.id')
            ->leftJoin('equipments ON equipments.customers_id = customers.id')
            ->leftJoin('edocuments ON edocuments.customers_id = customers.id')
            ->leftJoin('outlay ON outlay.customers_id = customers.id')
            ->select(null)
            ->select('customers.id')
            ->select('COUNT(DISTINCT interventions.id) AS nbInterventions')
            ->select('COUNT(DISTINCT equipments.id) AS nbEquipments')
            ->select('COUNT(DISTINCT edocuments.id) AS nbDocuments')
            ->select('COUNT(DISTINCT outlay.id) AS nbOutlay')
            ->where('customers.id = ?', [$id])
            ->fetch();
    }

    /**
     * For API
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws \Envms\FluentPDO\Exception
     */
    public function getCustomersByPage(
        int $page,
        int $perPage
    ): array {
        $total = $this->count();
        return $this->getResultsWithPagination($page, $perPage, [], $total);
    }

}