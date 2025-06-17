<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Update2011Model extends AbstractMigration
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
            ->addColumn('v1_id', 'integer', ['null' => true])
            ->update();

        $this->table('equipments')
            ->addColumn('v1_id', 'integer', ['null' => true])
            ->update();

        $this->table('brands')
            ->addColumn('v1_id', 'integer', ['null' => true])
            ->update();

        $this->table('interventions')
            ->addColumn('v1_id', 'integer', ['null' => true])
            ->update();

        $this->table('operating_systems')
            ->addColumn('v1_id', 'integer', ['null' => true])
            ->update();

        $this->table('types_equipments')
            ->addColumn('v1_id', 'integer', ['null' => true])
            ->update();

        $builder = $this->getQueryBuilder();
        $builder
            ->update('settings')
            ->set('app_version', '2.0.11')
            ->where(['id' => 1])
            ->execute();
    }
}
