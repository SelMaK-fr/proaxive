<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class EquipmentUpdateModel extends AbstractMigration
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
        $this->table('equipments')
            ->addColumn('localization_site', 'text', [
                'null' => true,
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM,
            ])
            ->addColumn('picture', 'string', [
                'null' => true
            ])
            ->update();
        ;
    }

    public function down(): void
    {
        $this->table('equipments')
            ->removeColumn('localization_site')
            ->removeColumn('picture')
            ->update();
    }
}
