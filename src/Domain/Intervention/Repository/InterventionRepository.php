<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Intervention\Repository;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Queries\Select;
use Pagerfanta\Pagerfanta;
use Selmak\Proaxive2\Domain\BaseRepository;
use Selmak\Proaxive2\Infrastructure\Paginator\PagerfantaQuery;

class InterventionRepository extends BaseRepository
{

    protected string $model = 'interventions';

    public function search(string $key): mixed
    {
        return $this->makeQueryDefault()
            ->select('e_serial')
            ->leftJoin('equipments as e ON e.id = interventions.equipments_id')
            ->where('interventions.customer_name LIKE ? OR e_serial LIKE ?', ["%".$key."%", "%".$key."%"])->orderBy('interventions.created_at DESC');

    }

    /**
     * @param string $reference
     * @return false|mixed
     * @throws Exception
     */
    public function findByReferenceLink(string $reference)
    {
        return $this->makeQueryObject()
            ->select('interventions.*, s.name s_name, s.color s_color, s.color_txt s_colortxt, s.description s_description')
            ->leftJoin('status as s ON s.id = interventions.status_id')
            ->where('ref_for_link = ?', [$reference])
            ->fetch()
            ;
    }

    /**
     * Permet d'effectuer une recherche par critère
     * @param array $data
     * @return \Envms\FluentPDO\Queries\Select
     * @throws Exception
     */
    public function searchByFields(array $data)
    {
        $query = $this->makeQueryDefault()
            ->select('interventions.*, s.name s_name, s.color s_color')
            ->leftJoin('status as s ON s.id = interventions.status_id')
            ;
        if (!empty($data['sort'])) {
            $query->where('interventions.sort = ?', $data['sort']);
        }
        if (!empty($data['status_id'])) {
            $query->where('interventions.status_id = ?', $data['status_id']);
        }
        if (!empty($data['way_steps'])) {
            $query->where('interventions.way_steps = ?', $data['way_steps']);
        }
        if (!empty($data['a_priority'])) {
            $query->where('interventions.a_priority = ?', $data['a_priority']);
        }
        if (!empty($data['users_id'])) {
            $query->where('interventions.users_id = ?', $data['users_id']);
        }
        if (!empty($data['company_id'])) {
            $query->where('interventions.company_id = ?', $data['company_id']);
        }
        return $query;
    }

    /**
     * Permet d'effectuer une recherche par critère avec pagination des éléments
     * USE PAGERFANTA
     * @param int $perPage
     * @param int $currentPage
     * @param array $data
     * @return Select
     * @throws Exception
     */
    public function searchByFieldsWithPaginate(int $perPage, int $currentPage, array $data)
    {
        $query = $this->makeQueryDefault()
            ->select('interventions.*, s.name s_name, s.color s_color, s.color_txt s_colortxt, s.description s_description')
            ->leftJoin('status as s ON s.id = interventions.status_id')
        ;
        if (!empty($data['sort'])) {
            $query->where('interventions.sort = ?', $data['sort']);
        }
        if (!empty($data['status_id'])) {
            $query->where('interventions.status_id = ?', $data['status_id']);
        }
        if (!empty($data['way_steps'])) {
            $query->where('interventions.way_steps = ?', $data['way_steps']);
        }
        if (!empty($data['a_priority'])) {
            $query->where('interventions.a_priority = ?', $data['a_priority']);
        }
        if (!empty($data['users_id'])) {
            $query->where('interventions.users_id = ?', $data['users_id']);
        }
        if (!empty($data['company_id'])) {
            $query->where('interventions.company_id = ?', $data['company_id']);
        }
        $req = new PagerfantaQuery($query);
        return (new Pagerfanta($req))
            ->setMaxPerPage((int)$perPage)
            ->setCurrentPage((int)$currentPage)
            ;
    }

    public function searchWithNumber(string $key): \Envms\FluentPDO\Queries\Select
    {
        return $this->makeQueryDefault()
            ->select('ref_number, ref_for_link')
            ->leftJoin('equipments as e ON e.id = interventions.equipments_id')
            ->where('interventions.ref_number LIKE ? ', ["%".$key."%"]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function joinForId(int $id)
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select('interventions.*, cy.name cy_name, cy.address cy_address, cy.phone cy_phone, cy.mobile cy_mobile, cy.mail cy_mail, 
            cy.zipcode cy_zipcode, cy.city cy_city, cy.created_at cy_created_at, cy.website cy_website, cy.logo cy_logo, cy.siret cy_siret, cy.aprm cy_aprm, cy.ape cy_ape, cy.rm cy_rm, cy.signature cy_signature, e.name e_name, e.brand_name e_brand_name, e.e_year e_year, e.e_model e_model, e.os_name e_os_name, e.id e_id, e.type_name e_type_name, e.e_serial e_serial, u.fullname u_fullname, u.roles u_roles, u.id u_id, 
            u.avatar u_avatar, e.created_at e_created_at, c.city c_city, c.zipcode c_zipcode, c.department c_department, c.phone c_phone,
            c.mobile c_mobile, c.favorite_contact c_favorite_contact, c.address c_address, c.zipcode c_zipcode, c.city c_city, c.mail c_mail, c.login_id c_login_id,
            d.is_signed d_is_signed, d.deposit_date d_deposit_date,
            s.name s_name, s.id s_id, s.color s_color, s.color_txt s_colortxt, s.description s_description')
            ->leftJoin('equipments as e ON e.id = interventions.equipments_id')
            ->leftJoin('users as u ON u.id = interventions.users_id')
            ->leftJoin('customers as c ON c.id = interventions.customers_id')
            ->leftJoin('company as cy ON cy.id = interventions.company_id')
            ->leftJoin('deposit as d ON d.reference = interventions.deposit_reference')
            ->leftJoin('status as s ON s.id = interventions.status_id')
            ->where('interventions.id = ?', [$id])
            ->fetch()
            ;
    }

    public function joinForIdWithKey(mixed $value)
    {
        return $this->makeQueryObject()
            ->select('interventions.*, interventions.id i_id, e.name e_name, e.id e_id, u.fullname u_fullname, u.roles u_roles, u.id u_id, u.avatar u_avatar, s.name s_name, s.id s_id, s.color s_color, s.color_txt s_colortxt, s.description s_description')
            ->leftJoin('equipments as e ON e.id = interventions.equipments_id')
            ->leftJoin('users as u ON u.id = interventions.users_id')
            ->leftJoin('status as s ON s.id = interventions.status_id')
            ->where('ref_for_link = ?', [$value])
            ->fetch()
            ;
    }

    public function allWithUser()
    {
        return $this->getInterventionsAndStatus(
            'u.fullname u_fullname, c.name cy_name',
            [
                'users as u ON u.id = interventions.users_id',
                'company as c ON c.id = interventions.company_id'
            ]
        );
    }

    public function allWithCompanyAndUser(mixed $limit = null, bool $isArray = false)
    {
        $query = $this->getInterventionsAndStatus(
            'u.fullname u_fullname, c.name cy_name',
            [
                'users as u ON u.id = interventions.users_id',
                'company as c ON c.id = interventions.company_id'
            ]
        )->orderBy('interventions.created_at DESC');
        if($isArray){
            return $query;
        } else {
            if($limit != null){
                $query->limit($limit);
            }
            return $query->fetchAll();
        }
    }

    /**
     * Permet de récupérer toutes les interventions URGENT et ABSOLUTE
     * SANS PAGINATION
     * Jointure Company, User et Status (défaut)
     * @return array|bool
     * @throws Exception
     */
    public function fullDataIfHot()
    {
        $query = $this->getInterventionsAndStatus(
            'u.fullname u_fullname, c.name cy_name',
            [
                'users as u ON u.id = interventions.users_id',
                'company as c ON c.id = interventions.company_id'
            ]
        )->where('interventions.a_priority = ? OR interventions.a_priority = ?', ['URGENT', 'ABSOLUTE']);
        return $query->fetchAll();
    }

    /**
     * Permet de récupérer les interventions normal avec une pagination (PagerFanta)
     * Jointure Company, User et Status (défaut)
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function findWithPaginate(int $perPage, int $currentPage, ?string $filter): Pagerfanta
    {
        if(!empty($filter)){
            $query = $this->allWithUser()->where('state = ?', $filter)->orderBy('interventions.created_at DESC');
        } else {
            $query = $this->allWithCompanyAndUser(null, true)->orderBy('interventions.created_at DESC');
        }
        $req = new PagerfantaQuery($query);
        return (new Pagerfanta($req))
            ->setMaxPerPage((int)$perPage)
            ->setCurrentPage((int)$currentPage)
            ;
    }

    /**
     * Params ['customers_id']
     * @param int $perPage
     * @param int $currentPage
     * @param array|null $params
     * @return Pagerfanta
     * @throws Exception
     *
     */
    public function findByCustomerWithPaginate(int $perPage, int $currentPage, ?array $params = []): Pagerfanta
    {

        $query = $this->getInterventionsAndStatus(
            'u.fullname u_fullname, cy.name cy_name',
            [
                'users as u ON u.id = interventions.users_id',
                'company as cy ON cy.id = interventions.company_id',
            ]
        )->where('interventions.customers_id = ? AND interventions.state != ?',
            [
                $params['customers_id'],
                'PENDING'
            ]
        );
        $req = new PagerfantaQuery($query);
        return (new Pagerfanta($req))
            ->setMaxPerPage((int)$perPage)
            ->setCurrentPage((int)$currentPage)
            ;
    }

    public function findWithCustomer($id)
    {
        return $this->makeQueryDefault()
            ->select('interventions.*, c.fullname c_fullname, c.mail, cy.logo cy_logo, cy.name cy_name, cy.type cy_type, s.name s_name, s.id s_id, s.color s_color,  s.color_txt s_colortxt, s.description s_description')
            ->leftJoin('customers as c ON c.id = interventions.customers_id')
            ->leftJoin('company as cy ON cy.id = interventions.company_id')
            ->leftJoin('status as s ON s.id = interventions.status_id')
            ->where('interventions.id = ?', [$id])
            ->fetch()
            ;
    }

    // For API

    public function apiStats()
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select('way_steps, COUNT(*) AS count')
            ->groupBy('way_steps')
            ->fetchAll()
            ;
    }

    /**
     * Permet de retourner une requête construite avec une jointure sur la table status
     * @param string|null $sql
     * @param array|null $joins
     * @return \Envms\FluentPDO\Queries\Select
     * @throws Exception
     */
    private function getInterventionsAndStatus(?string $sql = null, ?array $joins = [])
    {
        $statement = 'interventions.*, s.name s_name, s.id s_id, s.color s_color, s.color_txt s_colortxt, s.description s_description, ';
        if($sql != null){
            $statement .= $sql;
        }
        $query = $this->makeQueryDefault()
            ->select($statement)
            ->leftJoin('status as s ON s.id = interventions.status_id')
            ;
        if(!empty($joins)) {
            foreach($joins as $join) {
                $query->leftJoin($join);
            }
        }
        return $query;
    }
}