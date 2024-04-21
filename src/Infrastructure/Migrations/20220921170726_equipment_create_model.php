<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EquipmentCreateModel extends AbstractMigration
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
        $this->table('equipments')
            ->addColumn('name', 'string')
            ->addColumn('about', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('slug', 'string', ['null' => true])
            ->addColumn('e_serial', 'string', ['null' => true])
            ->addColumn('e_year', 'string', ['null' => true])
            ->addColumn('e_model', 'string', ['null' => true])
            ->addColumn('e_licence', 'string', ['null' => true])
            ->addColumn('end_guarantee', 'string', ['null' => true])
            ->addColumn('customer_name', 'string', ['null' => true])
            ->addColumn('type_name', 'string', ['null' => true])
            ->addColumn('brand_name', 'string', ['null' => true])
            ->addColumn('os_name', 'string', ['null' => true])
            ->addColumn('is_outofservice', 'boolean', ['null' => true])
            // Computer
            ->addColumn('c_install_date', 'string', ['null' => true])
            ->addColumn('c_processor', 'string', ['null' => true])
            ->addColumn('c_motherboard', 'string', ['null' => true])
            ->addColumn('c_socket', 'string', ['null' => true])
            ->addColumn('c_bios', 'string', ['null' => true])
            ->addColumn('c_gpu', 'string', ['null' => true])
            ->addColumn('c_memory', 'string', ['null' => true])
            ->addColumn('c_hdd0', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('c_hdd1', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('c_hdd2', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('c_hdd3', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('c_software', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            // Netwotk
            ->addColumn('n_ipaddress', 'string', ['null' => true])
            ->addColumn('n_gateway', 'string', ['null' => true])
            ->addColumn('n_masknetwork', 'string', ['null' => true])
            ->addColumn('n_dns', 'string', ['null' => true])
            ->addColumn('n_ssid', 'string', ['null' => true])
            ->addColumn('n_wifi_key', 'string', ['null' => true])
            // User
            ->addColumn('u_username', 'string', ['null' => true])
            ->addColumn('u_account_mail', 'string', ['null' => true])
            ->addColumn('u_password', 'string', ['null' => true])
            ->addColumn('u_domain', 'string', ['null' => true])
            // BAO
            ->addColumn('bao_temp_file', 'string', ['null' => true])
            // ID
            ->addColumn('customers_id', 'integer', ['null' => true])
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('brands_id', 'integer', ['null' => true])
            ->addForeignKey('brands_id', 'brands', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('types_equipments_id', 'integer', ['null' => true])
            ->addForeignKey('types_equipments_id', 'types_equipments', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('operating_systems_id', 'integer', ['null' => true])
            ->addForeignKey('operating_systems_id', 'operating_systems', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
