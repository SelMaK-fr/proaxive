<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Deposit\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class DepositRepository extends BaseRepository
{

    protected string $model = 'deposit';

    public function findByReference($reference)
    {
        return $this->makeQueryObject()
            ->where('reference = ?', [$reference])
            ->fetch()
            ;
    }

    public function joinForId(int $id)
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select('deposit.*, i.ref_number i_reference, i.ref_for_link, i.package_price, i.description, cy.name cy_name, cy.address cy_address, cy.phone cy_phone, cy.mail cy_mail, 
            cy.zipcode cy_zipcode, cy.city cy_city, cy.siret, cy.ape, cy.rc_pro, cy.type cy_type, cy.created_at cy_created_at, e.name e_name, e.id e_id, e.type_name e_type_name, e.e_serial e_serial, e.brand_name e_brand_name, e.created_at e_created_at, c.city c_city, c.zipcode c_zipcode, c.department c_department, c.phone c_phone,
            c.mobile c_mobile, c.favorite_contact c_favorite_contact, c.address c_address, c.mail c_mail, c.login_id,
            deposit.is_signed d_is_signed')
            ->leftJoin('equipments as e ON e.id = deposit.equipments_id')
            ->leftJoin('interventions as i ON i.id = deposit.interventions_id')
            ->leftJoin('customers as c ON c.id = deposit.customers_id')
            ->leftJoin('company as cy ON cy.id = deposit.company_id')
            ->where('deposit.id = ?', [$id])
            ->fetch()
            ;
    }
}