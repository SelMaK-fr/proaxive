<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class NoteUpdate208Model extends AbstractMigration
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
        $this->table('notes')
            ->addColumn('title', 'string', ['null' => true])
            ->addColumn('content', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('stick', 'integer', ['null' => true])
            ->addColumn('high', 'integer', ['null' => true])
            ->addColumn('archived', 'integer', ['null' => true])
            ->addColumn('bgcolor', 'string', ['null' => true])
            ->addColumn('txtcolor', 'string', ['null' => true])
            ->addColumn('users_id', 'integer', ['null' => true])
            ->addForeignKey('users_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('company_id', 'integer', ['null' => true])
            ->addForeignKey('company_id', 'company', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
