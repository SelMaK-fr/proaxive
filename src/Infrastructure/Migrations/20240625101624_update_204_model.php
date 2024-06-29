<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Update204Model extends AbstractMigration
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
        $this->table('interventions')
            ->addColumn('deposit_reference', 'string', ['limit' => 100, 'null' => true])
            ->update();
        $this->table('customers')
            ->addColumn('lastname', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('firstname', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('civility', 'string', ['limit' => 100, 'null' => true])
            ->update();
    }

    /**
     * Migrate Down.
     */
    public function down(): void
    {

    }
}
