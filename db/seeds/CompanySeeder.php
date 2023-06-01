<?php
use Phinx\Seed\AbstractSeed;
class CompanySeeder extends AbstractSeed
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
        $data = [
            [
                'cname' => 'Mon entreprise',
                'about' => null,
                'type' => 'Société',
                'adress' => 'Mon adresse postale',
                'city' => null,
                'country' => 'France',
                'zipcode' => null,
                'department_id' => 15,
                'phone' => null,
                'mobile' => null,
                'fax' => null,
                'phone_indicatif' => '+33',
                'director' => 'John Doe',
                'website' => null,
                'mail' => null,
                'siret' => null,
                'ape' => null,
                'aprm' => null,
                'rm' => null,
                'rcs' => null,
                'rc_pro' => null,
                'tva' => null,
                'facebook' => null,
                'twitter' => null,
                'instagram' => null,
                'linkedin' => null,
                'logo' => null,
                'isdefault' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        $this->insert('company', $data);
    }
}