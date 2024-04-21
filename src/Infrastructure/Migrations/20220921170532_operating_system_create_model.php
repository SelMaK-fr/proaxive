<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OperatingSystemCreateModel extends AbstractMigration
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
        $this->table('operating_systems')
            ->addColumn('os_name', 'string')
            ->addColumn('os_release', 'string', ['null' => true])
            ->addColumn('os_architecture', 'string', ['null' => true])
            ->addColumn('os_about', 'text', ['null' => true])
            ->addColumn('os_full', 'string', ['null' => true])
            ->create();
    }
}
