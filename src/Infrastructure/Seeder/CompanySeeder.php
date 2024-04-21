<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Seeder;

use Phinx\Seed\AbstractSeed;

class CompanySeeder extends AbstractSeed
{

    public function run()
    {
        $company = [
            [
                'name' => 'Mon entreprise',
                'about' => null,
                'type' => 'EURL',
                'address' => 'Mon adresse postale',
                'city' => null,
                'country' => 'France',
                'zipcode' => null,
                'department' => null,
                'addr_longitude' => null,
                'addr_latitude' => null,
                'phone' => '0102030405',
                'mobile' => null,
                'fax' => null,
                'phone_indicatif' => '+33',
                'director' => 'John Doe',
                'website' => null,
                'mail' => 'myname@myenterprise.fr',
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
        $this->insert('company', $company);
    }
}