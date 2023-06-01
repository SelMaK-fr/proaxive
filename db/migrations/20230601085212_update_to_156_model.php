<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateTo156Model extends AbstractMigration
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

        $this->table('equipments')
            ->addColumn('u_username', 'string', ['null' => true])
            ->addColumn('u_account_mail', 'string', ['null' => true])
            ->addColumn('u_password', 'string', ['null' => true])
            ->addColumn('u_domain', 'string', ['null' => true])
            ->addColumn('n_ipaddress', 'string', ['null' => true])
            ->addColumn('n_gateway', 'string', ['null' => true])
            ->addColumn('n_masknetwork', 'string', ['null' => true])
            ->addColumn('n_dns', 'string', ['null' => true])
            ->addColumn('n_ssid', 'string', ['null' => true])
            ->addColumn('n_wifi_key', 'text', ['null' => true])
            ->update();

        $builder = $this->getQueryBuilder();
        $builder
            ->update('pl15x_settings')
            ->set('version', '1.5.6')
            ->where(['id' => 1])
            ->execute();
    }
}
