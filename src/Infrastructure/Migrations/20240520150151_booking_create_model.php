<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class BookingCreateModel extends AbstractMigration
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
        $this->table('booking')
            ->addColumn('begin_at', 'datetime')
            ->addColumn('end_at', 'datetime', ['null' => true])
            ->addColumn('title', 'string')
            ->addColumn('subtitle', 'string', ['null' => true])
            ->addColumn('description', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('backgroundColor', 'string', ['null' => true])
            ->addColumn('textColor', 'string', ['null' => true])
            ->addColumn('allDay', 'boolean', ['null' => true])
            ->addColumn('component', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->create();
    }
}
