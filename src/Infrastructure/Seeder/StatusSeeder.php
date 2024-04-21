<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Seeder;

use Phinx\Seed\AbstractSeed;

class StatusSeeder extends AbstractSeed
{

    public function run()
    {
        $status = [
            [
                'name' => 'En traitement',
                'color' => null,
                'fixed' => 1
            ],
            [
                'name' => 'En attente de récupération',
                'color' => null,
                'fixed' => 1
            ],
            [
                'name' => 'Réparé',
                'color' => null,
                'fixed' => 1
            ],
            [
                'name' => 'Devis approuvé',
                'color' => null,
                'fixed' => 1
            ],
            [
                'name' => 'Devis refusé',
                'color' => null,
                'fixed' => 1
            ],
        ];
        $this->insert('status', $status);
    }
}