<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserCreateModel extends AbstractMigration
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
        $this->table('users')
            ->addColumn('pseudo', 'string')
            ->addColumn('mail', 'string')
            ->addIndex(['mail'], ['unique' => true])
            ->addColumn('fullname', 'string', ['null' => true])
            ->addColumn('password','text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('key_totp', 'string', ['null' => true])
            ->addColumn('lastvisite', 'date', ['null' => true])
            ->addColumn('avatar', 'string', ['null' => true])
            ->addColumn('resetpassword', 'string', ['null' => true])
            ->addColumn('confirm_at', 'string', ['null' => true])
            ->addColumn('address_ip', 'string', ['null' => true])
            ->addColumn('token', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_REGULAR,
                'null' => true
            ])
            ->addColumn('auth_token', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_REGULAR,
                'null' => true
            ])
            ->addColumn('reset_at', 'datetime', ['null' => true])
            ->addColumn('reset_code', 'text',[
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('roles', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
