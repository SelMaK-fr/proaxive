<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TypeSeeder extends AbstractSeed
{
    public function run()
    {
        $types = [
            [
                'name' => 'Ordinateur de bureau',
                'slug' => 'desktop',
                'is_peripheral' => null
            ],
            [
                'name' => 'Ordinateur portable',
                'slug' => 'laptop',
                'is_peripheral' => null
            ],
            [
                'name' => 'Serveur',
                'slug' => 'server',
                'is_peripheral' => null
            ],
            [
                'name' => 'Smartphone',
                'slug' => 'smartphone',
                'is_peripheral' => null
            ],
            [
                'name' => 'Tablette',
                'slug' => 'tablet',
                'is_peripheral' => null
            ],
            [
                'name' => 'Imprimante',
                'slug' => 'printer',
                'is_peripheral' => 1
            ],
            [
                'name' => 'Scanner',
                'slug' => 'scan',
                'is_peripheral' => 1
            ],
            [
                'name' => 'SSD Externe',
                'slug' => 'external-ssd',
                'is_peripheral' => 1
            ],
            [
                'name' => 'HDD Externe',
                'slug' => 'external-hdd',
                'is_peripheral' => 1
            ],
        ];

        $this->insert('types_equipments', $types);
    }
}