<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OutlayCreateModel extends AbstractMigration
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
        $this->table('outlay')
            ->addColumn('reference', 'string')
            ->addColumn('amount', 'string')
            ->addColumn('refund', 'date', ['null' => true])
            ->addColumn('payment_method', 'string', ['null' => true])
            ->addColumn('reference_propal', 'string', ['null' => true])
            ->addColumn('reference_intervention', 'string', ['null' => true])
            ->addColumn('is_closed', 'boolean', ['null' => true])
            ->addColumn('seller', 'string', ['null' => true])
            ->addColumn('products', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('status', 'string', ['null' => true])
            ->addColumn('customers_id', 'integer', ['null' => true])
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('is_approved', 'boolean', ['null' => true])
            ->addColumn('users_id', 'integer', ['null' => true])
            ->addForeignKey('users_id', 'users', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('code_sign', 'text', [
                    'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                    'null' => true]
            )
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
