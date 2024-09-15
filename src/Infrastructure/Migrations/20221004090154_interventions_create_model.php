<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InterventionsCreateModel extends AbstractMigration
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
    public function up(): void
    {
        $this->table('interventions')
            ->addColumn('name', 'string')
            ->addColumn('sort', 'string')
            ->addColumn('with_deposit', 'boolean', ['null' => true])
            ->addColumn('description', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('before_breakdown', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('observation', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('note_technician', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('note_customer', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('handling_customer', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('message_report', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('actions_list', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('bao_report_file', 'text', ['null' => true])
            ->addColumn('way_type', 'string', ['null' => true])
            ->addColumn('way_steps', 'integer', ['null' => true])
            ->addColumn('customer_name', 'string', ['null' => true])
            ->addColumn('equipment_name', 'string', ['null' => true])
            ->addColumn('a_priority', 'string', ['null' => true])
            ->addColumn('is_remote', 'boolean', ['null' => true])
            ->addColumn('ref_number', 'string')
            ->addColumn('ref_for_link', 'string')
            ->addColumn('state', 'string')
            ->addColumn('package_price_name', 'string', ['null' => true])
            ->addColumn('package_price', 'string', ['null' => true])
            ->addColumn('total_time', 'integer', ['null' => true])
            ->addColumn('is_closed', 'boolean', ['null' => true])
            ->addColumn('diag_cpu', 'string', ['null' => true])
            ->addColumn('diag_gpu', 'string', ['null' => true])
            ->addColumn('diag_memory', 'string', ['null' => true])
            ->addColumn('diag_storage', 'string', ['null' => true])
            ->addColumn('customers_id', 'integer', ['null' => true])
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('equipments_id', 'integer', ['null' => true])
            ->addForeignKey('equipments_id', 'equipments', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('users_id', 'integer', ['null' => true])
            ->addForeignKey('users_id', 'users', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('start_date', 'datetime', ['null' => true])
            ->addColumn('end_date', 'datetime', ['null' => true])
            ->addColumn('deposit_date', 'date', ['null' => true])
            ->addColumn('pull_date', 'datetime', ['null' => true])
            ->addColumn('cancel_date', 'datetime', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    public function down(): void
    {
        $this->table('interventions')->drop()->update();
    }
}
