<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TypeInterventionUpdate207Model extends AbstractMigration
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
        $this->table('types_interventions')
            ->addColumn('name', 'string')
            ->addColumn('description', 'integer', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    public function down(): void
    {
        $this->table('types_interventions')
            ->drop()
            ->update();
    }
}
