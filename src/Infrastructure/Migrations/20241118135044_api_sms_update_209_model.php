<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ApiSmsUpdate209Model extends AbstractMigration
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
        $this->table('sms_api')
            ->addColumn('provider', 'string')
            ->addColumn('api_key', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG
            ])
            ->addColumn('secret_key', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG
            ])
            ->addColumn('consumer_key', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('twilio_number', 'text', ['null' => true])
            ->addColumn('is_default', 'integer')
            ->addColumn('users_id', 'integer', ['null' => true])
            ->addForeignKey('users_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('company_id', 'integer', ['null' => true])
            ->addForeignKey('company_id', 'company', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
        // Create table for templates
        $this->table('sms_templates')
            ->addColumn('tpl_name', 'text')
            ->addColumn('tpl_content', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_MEDIUM
            ])
            ->create();
    }
}
