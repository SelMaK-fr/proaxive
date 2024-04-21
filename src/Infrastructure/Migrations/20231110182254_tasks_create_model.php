<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TasksCreateModel extends AbstractMigration
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
        $this->table('tasks')
            ->addColumn('name', 'string')
            ->addColumn('price', 'float', ['null' => true])
            ->addColumn('description', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->create();

        // Association table
        $this->table('tasks_assoc')
            ->addColumn('tasks_id', 'integer')
            ->addForeignKey('tasks_id', 'tasks', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('interventions_id', 'integer')
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}