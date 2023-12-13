<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CustomerCreateModel extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('customers')
            ->addColumn('mail', 'string', ['null' => true])
            ->addColumn('passwd', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG, 'null' => true])
            ->addColumn('activated', 'integer')
            ->addColumn('login_id', 'string', ['null' => true])
            ->addColumn('fullname', 'string')
            ->addColumn('phone', 'string', ['null' => true])
            ->addColumn('mobile', 'string', ['null' => true])
            ->addColumn('on_sale', 'string', ['null' => true])
            ->addColumn('addr_longitude', 'string', ['null' => true])
            ->addColumn('addr_latitude', 'string', ['null' => true])
            ->addColumn('department', 'string', ['null' => true])
            ->addColumn('phone_indicatif', 'string', ['null' => true])
            ->addColumn('profil_type', 'string', ['null' => true])
            ->addColumn('favorite_contact', 'string', ['null' => true])
            ->addColumn('address', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM,
                'null' => true
            ])
            ->addColumn('zipcode', 'string', ['null' => true])
            ->addColumn('city', 'string', ['null' => true])
            ->addColumn('type_housing', 'string', ['null' => true])
            ->addColumn('h_floor', 'string', ['null' => true])
            ->addColumn('h_digicode', 'string', ['null' => true])
            ->addColumn('h_about',  'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            // Society
            ->addColumn('about', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('tva_number', 'string', ['null' => true])
            ->addColumn('type_status', 'string', ['null' => true])
            ->addColumn('siret_number', 'string', ['null' => true])
            ->addColumn('naf_number', 'string', ['null' => true])
            ->addColumn('phone_2', 'string', ['null' => true])
            ->addColumn('website', 'string', ['null' => true])
            ->addColumn('contact_status', 'string', ['null' => true])
            ->addColumn('contact_phone', 'string', ['null' => true])
            ->addColumn('contact_fullname', 'string', ['null' => true])
            ->addColumn('contact_mail', 'string', ['null' => true])
            ->addColumn('contact_address', 'text', ['null' => true])
            ->addColumn('token_access', 'text', ['null' => true])
            ->addColumn('is_society', 'boolean', ['null' => true])
            ->addColumn('is_blacklisted', 'boolean', ['null' => true])
            ->addColumn('enable_portal', 'boolean', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
