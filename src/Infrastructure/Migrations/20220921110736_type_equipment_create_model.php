<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TypeEquipmentCreateModel extends AbstractMigration
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
        $this->table('types_equipments')
            ->addColumn('name', 'string', ['null' => true])
            ->addColumn('slug', 'string', ['null' => true])
            ->addColumn('is_peripheral', 'boolean', ['null' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('types_equipments')->drop()->update();
    }
}
