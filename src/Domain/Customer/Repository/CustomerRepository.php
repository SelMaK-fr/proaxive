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