<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Update205Model extends AbstractMigration
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
            ->addColumn('open_days', 'string', ['null' => true, 'default' => 'Du lundi au vendredi'])
            ->addColumn('open_hours', 'string', ['null' => true, 'default' => '9H - 12H / 14H - 18H'])
            ->update();
        $this->table('status')
            ->addColumn('description', 'text', [
                'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG,
                'null' => true
            ])
            ->addColumn('color_txt', 'string', ['null' => true])
            ->update();
        $this->table('settings')
            ->addColumn('app_version', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('is_beta', 'integer')
            ->create();
        $this->table('interventions')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('equipments_id')
            ->addForeignKey('equipments_id', 'equipments', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->addColumn('status_id', 'integer', ['null' => true])
            ->addForeignKey('status_id', 'status', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('outlay')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('equipments')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('edocuments')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('interventions_id')
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('deposit')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('interventions_id')
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('equipments_id')
            ->addForeignKey('equipments_id', 'equipments', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->update();
        // Update system
        $builder = $this->getQueryBuilder();
        $builder
            ->insert(['app_version', 'is_beta'])
            ->into('settings')
            ->values(['app_version' => '2.0.5', 'is_beta' => 1])
            ->execute();
        // Update status
        $builder
            ->update('status')
            ->into('settings')
            ->set([
                'color' => 'rgba(0, 119, 193, 0.2)',
                'color_txt' => '#0094c1',
            ])
            ->where(['id' => 1])
            ->execute();
        $builder
            ->update('status')
            ->into('settings')
            ->set([
                'color' => 'rgba(0, 119, 193, 0.2)',
                'color_txt' => '#0094c1',
            ])
            ->where(['id' => 2])
            ->execute();
        $builder
            ->update('status')
            ->into('settings')
            ->set([
                'name' => 'En attente',
                'color' => 'rgba(0, 119, 193, 0.2)',
                'color_txt' => '#0094c1',
            ])
            ->where(['id' => 3])
            ->execute();
        $builder
            ->update('status')
            ->into('settings')
            ->set([
                'name' => 'Complété(e)',
                'description' => 'Intervention cloturée le :',
                'color' => 'rgba(75, 192, 192, 0.2)',
                'color_txt' => '#4bc0c0',
            ])
            ->where(['id' => 4])
            ->execute();
        $builder
            ->update('status')
            ->into('settings')
            ->set([
                'name' => 'Annulé(e)',
                'color' => 'rgba(247, 49, 100, 0.15)',
                'color_txt' => '#f73164',
            ])
            ->where(['id' => 5])
            ->execute();
    }

    /**
     * Migrate Down.
     */
    public function down(): void
    {
        $this->table('company')
            ->removeColumn('open_days')
            ->removeColumn('open_hours')
            ->update();
        $this->table('status')
            ->removeColumn('description')
            ->removeColumn('color_txt')
            ->update();
        $this->table('settings')
            ->drop()
            ->update();
        $this->table('interventions')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('equipments_id')
            ->addForeignKey('equipments_id', 'equipments', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('outlay')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('equipments')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('edocuments')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('interventions_id')
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
        $this->table('deposit')
            ->dropForeignKey('customers_id')
            ->addForeignKey('customers_id', 'customers', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('interventions_id')
            ->addForeignKey('interventions_id', 'interventions', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->dropForeignKey('equipments_id')
            ->addForeignKey('equipments_id', 'equipments', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION'
            ])
            ->update();
    }
}
