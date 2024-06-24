<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TasksSeeder extends AbstractSeed
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
        $tasks = [
            [
                'name' => 'Tentative de réinitialisation',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Mises à jour Windows',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Installation des logiciels basiques',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Mise en place SSD',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Installation Windows 10',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Activation de Windows 10',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Récupération de données',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Formatage HDD',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Installation/Activation Office 2021 Pro Plus',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Nettoyage du boitier',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Restauration sauvegarde HDD vers SSD',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Nettoyage ventirad CPU',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Sauvegarde des données utilisateur',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Premier test de démarrage',
                'price' => null,
                'description' => null
            ],
            [
                'name' => 'Installation des mises à jour et pilotes',
                'price' => null,
                'description' => null
            ],
        ];

        $this->insert('tasks', $tasks);
    }
}