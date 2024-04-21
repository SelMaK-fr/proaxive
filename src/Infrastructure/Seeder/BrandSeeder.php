<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Seeder;

use Phinx\Seed\AbstractSeed;

class BrandSeeder extends AbstractSeed
{

    public function run()
    {
        $brands = [
            [
                'name' => 'Acer',
                'logo_link' => null,
                'slug' => 'acer',
                'logo_file' => 'pe190873615-acer.png'
            ],
            [
                'name' => 'Asus',
                'logo_link' => null,
                'slug' => 'asus',
                'logo_file' => 'pe59894369-asus.png'
            ],
            [
                'name' => 'Apple',
                'logo_link' => null,
                'slug' => 'apple',
                'logo_file' => 'pe938389281-apple.png'
            ],
            [
                'name' => 'Brother',
                'logo_link' => null,
                'slug' => 'brother',
                'logo_file' => 'pe847625144-brother.png'
            ],
            [
                'name' => 'Canon',
                'logo_link' => null,
                'slug' => 'canon',
                'logo_file' => 'pe303630207-canon.png'
            ],
            [
                'name' => 'Compaq',
                'logo_link' => null,
                'slug' => 'compaq',
                'logo_file' => 'pe762190526-compaq.png'
            ],
            [
                'name' => 'Cisco',
                'logo_link' => null,
                'slug' => 'cisco',
                'logo_file' => 'pe705228956-Cisco.png'
            ],
            [
                'name' => 'Dell',
                'logo_link' => null,
                'slug' => 'dell',
                'logo_file' => 'pe279357682-dell.png'
            ],
            [
                'name' => 'Epson',
                'logo_link' => null,
                'slug' => 'epson',
                'logo_file' => 'pe47222901-epson.png'
            ],
            [
                'name' => 'HP',
                'logo_link' => null,
                'slug' => 'hp',
                'logo_file' => 'pe826297485-hp.png'
            ],
            [
                'name' => 'Lenovo',
                'logo_link' => null,
                'slug' => 'lenovo',
                'logo_file' => 'pe718666919-lenovo.png'
            ],
            [
                'name' => 'Packard Bell',
                'logo_link' => null,
                'slug' => 'packard-bell',
                'logo_file' => 'pe381036515-packard-bell.png'
            ],
            [
                'name' => 'Samsung',
                'logo_link' => null,
                'slug' => 'samsung',
                'logo_file' => 'pe658144462-samsung.png'
            ],
            [
                'name' => 'Sony',
                'logo_link' => null,
                'slug' => 'sony',
                'logo_file' => 'pe441648698-sony.png'
            ],
            [
                'name' => 'Xiaomi',
                'logo_link' => null,
                'slug' => 'xiaomi',
                'logo_file' => 'pe197696821-xiaomi.png'
            ],
        ];

        $this->insert('brands', $brands);
    }
}