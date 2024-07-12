<?php
declare(strict_types=1);

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
                'name' => 'En attente',
                'color' => null,
                'fixed' => 1
            ],
            [
                'name' => 'Complété(e)',
                'color' => null,
                'fixed' => 1
            ],
            [
                'name' => 'Annulé(e)',
                'color' => null,
                'fixed' => 1
            ],
        ];
        $this->insert('status', $status);
    }
}