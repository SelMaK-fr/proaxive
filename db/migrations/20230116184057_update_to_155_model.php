<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateTo155Model extends AbstractMigration
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

        $this->table('company')
            ->addColumn('your_signature', 'text', ['null' => true])
            ->update();

        $this->table('ausers')
            ->addColumn('roles', 'text')
            ->update();

        $this->table('interventions')
            ->addColumn('approved', 'boolean', ['null' => true])
            ->update();

        $this->table('outlay')
            ->addColumn('approved', 'boolean', ['null' => true])
            ->addColumn('auser_id', 'integer', ['null' => true])
            ->addForeignKey('auser_id', 'ausers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();

        $builder = $this->getQueryBuilder();
        $builder
            ->update('pl15x_settings')
            ->set('version', '1.5.5')
            ->where(['id' => 1])
            ->execute();
    }
}
