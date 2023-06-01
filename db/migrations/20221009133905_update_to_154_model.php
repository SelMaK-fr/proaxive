<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateTo154Model extends AbstractMigration
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
        $this->table('interventions_tasks')
            ->addColumn('type', 'string', ['null' => true])
            ->addColumn('guilty', 'string', ['null' => true])
            ->addColumn('savedata', 'string', ['null' => true])
            ->addColumn('consumption', 'string', ['null' => true])
            ->addColumn('start_pc', 'string', ['null' => true])
            ->addColumn('bios_boot', 'string', ['null' => true])
            ->addColumn('start_os', 'string', ['null' => true])
            ->addColumn('errors_messages', 'string', ['null' => true])
            ->addColumn('video_display', 'string', ['null' => true])
            ->addColumn('memtest', 'string', ['null' => true])
            ->addColumn('fast_format', 'string', ['null' => true])
            ->addColumn('hard_format', 'string', ['null' => true])
            ->addColumn('chkdsk', 'string', ['null' => true])
            ->addColumn('restor_os', 'string', ['null' => true])
            ->addColumn('reinitial_os', 'string', ['null' => true])
            ->addColumn('check_os', 'string', ['null' => true])
            ->addColumn('reinstall_os', 'string', ['null' => true])
            ->addColumn('updates_os', 'string', ['null' => true])
            ->addColumn('clean_tower', 'string', ['null' => true])
            ->addColumn('clean_os', 'string', ['null' => true])
            ->addColumn('install_netbox', 'string', ['null' => true])
            ->addColumn('intervention_id', 'integer', ['null' => true])
            ->addForeignKey('intervention_id', 'interventions', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->create();

        // Settings Table
        $this->table('settings')
            ->addColumn('version', 'string', ['null' => true])
            ->addColumn('release', 'string', ['null' => true])
            ->create();
        $this->table('settings')
            ->insert(['id' => 1, 'version' => '1.5.4'])
            ->saveData();
    }
}
