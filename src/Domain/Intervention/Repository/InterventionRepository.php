<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Intervention\Repository;

use Envms\FluentPDO\Exception;
use Selmak\Proaxive2\Domain\BaseRepository;

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

    public function searchByFields(array $data)
    {
        $query = $this->makeQueryDefault()
            ->select('interventions.*')
            ;
        if (!empty($data['sort'])) {
            $query->where('interventions.sort = ?', $data['sort']);
        }
        if (!empty($data['way_type'])) {
            $query->where('interventions.way_type = ?', $data['way_type']);
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
            ->select('interventions.*, cy.name cy_name, cy.address cy_address, cy.phone cy_phone, cy.mail cy_mail, 
            cy.zipcode cy_zipcode, cy.city cy_city, cy.created_at cy_created_at, cy.website cy_website, cy.logo cy_logo, cy.siret cy_siret, cy.aprm cy_aprm, cy.rm cy_rm, cy.signature cy_signature, e.name e_name, e.brand_name e_brand_name, e.e_year e_year, e.e_model e_model, e.os_name e_os_name, e.id e_id, e.type_name e_type_name, e.e_serial e_serial, u.fullname u_fullname, u.roles u_roles, u.id u_id, 
            u.avatar u_avatar, e.created_at e_created_at, c.city c_city, c.zipcode c_zipcode, c.department c_department, c.phone c_phone,
            c.mobile c_mobile, c.favorite_contact c_favorite_contact, c.address c_address, c.zipcode c_zipcode, c.city c_city, c.mail c_mail,
            d.is_signed d_is_signed, d.deposit_date d_deposit_date')
            ->leftJoin('equipments as e ON e.id = interventions.equipments_id')
            ->leftJoin('users as u ON u.id = interventions.users_id')
            ->leftJoin('customers as c ON c.id = interventions.customers_id')
            ->leftJoin('company as cy ON cy.id = interventions.company_id')
            ->leftJoin('deposit as d ON d.reference = interventions.with_deposit')
            ->where('interventions.id = ?', [$id])
            ->fetch()
            ;
    }

    public function joinForIdWithKey(string $key, mixed $value)
    {
        return $this->makeQueryObject()
            ->select('interventions.*, c.*, interventions.id i_id, cy.name cy_name, cy.address cy_address, cy.phone cy_phone, cy.mail cy_mail, cy.zipcode cy_zipcode, cy.city cy_city, e.name e_name, e.id e_id, u.fullname u_fullname, u.roles u_roles, u.id u_id, u.avatar u_avatar')
            ->leftJoin('equipments as e ON e.id = interventions.equipments_id')
            ->leftJoin('users as u ON u.id = interventions.users_id')
            ->leftJoin('customers as c ON c.id = interventions.customers_id')
            ->leftJoin('company as cy ON cy.id = interventions.company_id')
            ->where($key. '= ?', [$value])
            ->fetch()
            ;
    }

    public function allWithUser()
    {
        return $this->makeQueryDefault()
            ->select('interventions.id id, u.fullname u_fullname')
            ->leftJoin('users as u ON u.id = interventions.users_id')
            ;
    }

    public function findWithCustomer($id)
    {
        return $this->makeQueryDefault()
            ->select('interventions.*, c.fullname c_fullname, c.mail')
            ->leftJoin('customers as c ON c.id = interventions.customers_id')
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
}