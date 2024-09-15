<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InterventionPicturesCreateModel extends AbstractMigration
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
        $this->table('intervention_pictures')
            ->addColumn('filename', 'string')
            ->addColumn('filesize', 'integer', ['null' => true])
            ->addColumn('picture_order', 'integer', ['null' => true])
            ->addColumn('interventions_id', 'integer', ['null' => true])
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    public function down(): void
    {
        $this->table('intervention_pictures')
            ->drop()
            ->update();
    }
}
