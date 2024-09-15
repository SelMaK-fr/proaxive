<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EdocumentCreateModel extends AbstractMigration
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
        $this->table('edocuments')
            ->addColumn('reference', 'string')
            ->addColumn('name', 'string')
            ->addColumn('filename', 'string')
            ->addColumn('size', 'string', ['null' => true])
            ->addColumn('extension', 'string', ['null' => true])
            ->addColumn('description', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('is_online', 'boolean', ['null' => true])
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
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    public function down(): void
    {
        $this->table('edocuments')
            ->drop()
            ->update();
    }
}
