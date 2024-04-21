<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class StatsModel extends AbstractMigration
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

        $this->table('statistics')
            ->addColumn('inter_not_started', 'integer', ['null' => true])
            ->addColumn('inter_in_workshop', 'integer', ['null' => true])
            ->addColumn('inter_final_test', 'integer', ['null' => true])
            ->addColumn('inter_exit_waiting', 'integer', ['null' => true])
            ->addColumn('inter_total', 'integer', ['null' => true])
            ->create();
            ;
    }
}
