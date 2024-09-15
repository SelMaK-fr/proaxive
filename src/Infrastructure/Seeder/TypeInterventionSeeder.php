<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TypeInterventionSeeder extends AbstractSeed
{
    public function run()
    {
        $typeI = [
            [
                'name' => 'Dépannage',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
            [
                'name' => 'Réparation',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
            [
                'name' => 'Visite périodique',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
            [
                'name' => 'Préparation de poste',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
            [
                'name' => 'Assemblage',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
            [
                'name' => 'Expertise',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
            [
                'name' => 'Devis',
                'description' => null,
                'created_at' => '2024-09-08 18:21:52',
                'updated_at' => '2024-09-08 18:21:52'
            ],
        ];

        $this->insert('types_interventions', $typeI);
    }
}