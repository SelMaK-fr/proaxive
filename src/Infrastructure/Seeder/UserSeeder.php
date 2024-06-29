<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $user = [
            'pseudo' => 'admin',
            'mail' => 'admin@proaxive.app',
            'fullname' => 'John Doe',
            'password' => password_hash('admin/admin', PASSWORD_DEFAULT),
            'roles' => 'SUPER_ADMIN',
            'company_id' => 1,
            'created_at' => '2024-03-25 04:21:52',
            'updated_at' => '2024-03-25 04:21:52'
        ];

        $this->insert('users', $user);
    }
}