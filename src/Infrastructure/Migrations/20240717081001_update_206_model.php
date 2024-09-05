<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Update206Model extends AbstractMigration
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
        $this->table('interventions_history')
            ->addColumn('message', 'text', [
                    'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG]
            )
            ->addColumn('type', 'string')
            ->addColumn('interventions_id', 'integer', ['null' => true])
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('users_id', 'integer', ['null' => true])
            ->addForeignKey('users_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('created_at', 'datetime')
            ->create();
        // Update system
        $builder = $this->getQueryBuilder();
        $builder
            ->update('settings')
            ->set('app_version', '2.0.6')
            ->where(['id' => 1])
            ->execute();
    }

    public function down(): void
    {
        $this->table('interventions_history')
            ->drop()
            ->update();
        // Update system
        $builder = $this->getQueryBuilder();
        $builder
            ->update('settings')
            ->set('app_version', '2.0.5')
            ->where(['id' => 1])
            ->execute();
    }
}
