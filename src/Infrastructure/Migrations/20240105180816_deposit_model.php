<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DepositModel extends AbstractMigration
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
        $this->table('deposit')
            ->addColumn('reference', 'string')
            ->addColumn('deposit_date', 'string')
            ->addColumn('customer_name', 'string')
            ->addColumn('equipment_name', 'string')
            ->addColumn('intervention_number', 'string')
            ->addColumn('equipment_state', 'integer')
            ->addColumn('about_state', 'text', ['null' => true])
            ->addColumn('equipment_accessories', 'text', ['null' => true])
            ->addColumn('is_signed', 'boolean', ['null' => true])
            ->addColumn('interventions_id', 'integer', ['null' => true])
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('customers_id', 'integer', ['null' => true])
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('company_id', 'integer', ['null' => true])
            ->addForeignKey('company_id', 'company', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('equipments_id', 'integer', ['null' => true])
            ->addForeignKey('equipments_id', 'equipments', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
