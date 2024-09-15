<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CompanyCreateModel extends AbstractMigration
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
        $this->table('company')
            ->addColumn('name', 'string')
            ->addColumn('about', 'text', ['null' => true])
            ->addColumn('type', 'string')
            ->addColumn('address', 'string', ['null' => true])
            ->addColumn('city', 'string', ['null' => true])
            ->addColumn('country', 'string', ['null' => true])
            ->addColumn('zipcode', 'string', ['null' => true])
            ->addColumn('department', 'string', ['null' => true])
            ->addColumn('addr_longitude', 'string', ['null' => true])
            ->addColumn('addr_latitude', 'string', ['null' => true])
            ->addColumn('phone', 'string')
            ->addColumn('mobile', 'string', ['null' => true])
            ->addColumn('fax', 'string', ['null' => true])
            ->addColumn('phone_indicatif', 'string', ['null' => true])
            ->addColumn('director', 'string', ['null' => true])
            ->addColumn('website', 'string', ['null' => true])
            ->addColumn('mail', 'string')
            ->addColumn('siret', 'string', ['null' => true])
            ->addColumn('ape', 'string', ['null' => true])
            ->addColumn('aprm', 'string', ['null' => true])
            ->addColumn('rm', 'string', ['null' => true])
            ->addColumn('rcs', 'string', ['null' => true])
            ->addColumn('rc_pro', 'string', ['null' => true])
            ->addColumn('tva', 'string', ['null' => true])
            ->addColumn('facebook', 'string', ['null' => true])
            ->addColumn('twitter', 'string', ['null' => true])
            ->addColumn('instagram', 'string', ['null' => true])
            ->addColumn('linkedin', 'string', ['null' => true])
            ->addColumn('logo', 'string', ['null' => true])
            ->addColumn('signature', 'string', ['null' => true])
            ->addColumn('isdefault', 'integer', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    public function down(): void
    {
        $this->table('company')->drop()->update();
    }
}
