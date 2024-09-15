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
    public function up(): void
    {
        $this->table('booking')
            ->addColumn('start_date', 'date')
            ->addColumn('end_date', 'date', ['null' => true])
            ->addColumn('start_time', 'time')
            ->addColumn('end_time', 'time', ['null' => true])
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
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    public function down(): void
    {
        $this->table('booking')
            ->drop()
            ->update();
    }
}
