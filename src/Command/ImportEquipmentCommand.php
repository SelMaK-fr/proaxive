<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Command;

use Envms\FluentPDO\Query;
use League\Csv\Reader;
use Selmak\Proaxive2\Domain\Equipment\Equipment;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportEquipmentCommand extends Command
{
    private Query $query;
    private SettingsInterface $settings;

    public function __construct(Query $query, SettingsInterface $settings)
    {
        parent::__construct();
        $this->query = $query;
        $this->settings = $settings;
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('csv:import:equipments');
        $this->setDescription('For import equipments');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Tentative d'importation des données Equipements...");
        $reader = Reader::createFromPath(__DIR__ . '/../../storage/app/imports/ProaxiveLite_Export-Equipments.csv', 'r');
        $reader->setHeaderOffset(0);
        $io->progressStart(iterator_count($reader));
        $repo = new EquipmentRepository($this->query);
        foreach($reader->getRecords() as $row) {
            $equipment = new Equipment();
            $repo->add($equipment, true);
            $io->progressAdvance();
        }
        $io->progressFinish();
        $io->success("La commande s'est terminée correctement !");
    }
}