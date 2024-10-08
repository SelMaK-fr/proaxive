<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UsersToCompanyModel extends AbstractMigration
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
        $this->table('users')
            ->addColumn('company_id', 'integer', ['null' => true])
            ->addForeignKey('company_id', 'company', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('users')
            ->removeColumn('company_id')
            ->dropForeignKey('company_id')
            ->update();
    }
}
