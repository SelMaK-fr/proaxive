<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Update207Model extends AbstractMigration
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
        // Gallery for intervention
        $this->table('intervention_pictures')
            ->addColumn('is_online', 'boolean', ['null' => true, 'default' => false])
            ->addColumn('name', 'string')
            ->addColumn('description', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->update();
        // OS
        $this->table('operating_systems')
            ->addColumn('os_order', 'integer', ['null' => true, 'default' => 1])
            ->update();
        // Update system
        $builder = $this->getQueryBuilder();
        $builder
            ->update('settings')
            ->set('app_version', '2.0.7')
            ->where(['id' => 1])
            ->execute();
    }

    public function down(): void
    {
        $this->table('intervention_pictures')
            ->removeColumn('is_online')
            ->removeColumn('description')
            ->update();
        $this->table('operating_systems')
            ->removeColumn('os_order')
            ->update();
        // Update system
        $builder = $this->getQueryBuilder();
        $builder
            ->update('settings')
            ->set('app_version', '2.0.6')
            ->where(['id' => 1])
            ->execute();
    }
}
