<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Command;

use Envms\FluentPDO\Query;
use Psr\Log\LoggerInterface;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\BrandImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\CustomerImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\EquipmentImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\EquipmentTypeImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\InterventionImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\OperatingSystemImporter;
use Selmak\Proaxive2\Settings\SettingsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportFromProaxiveV1Command extends Command
{

    public function __construct(
        private LoggerInterface $logger,
        private Query $query,
        private SettingsInterface $settings
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('csv:import:all');
        $this->setDescription('Import Data Proaxive v1.5.7 to Proaxive v2.0.x');
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Effectuer un test sans insérer en base')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Chemin personnalisé vers les fichiers CSV', $this->settings->get('storage')['app'] . 'imports/');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Symfony Console Style
        $io = new SymfonyStyle($input, $output);

        // Options
        $dryRun = $input->getOption('dry-run');
        $path = rtrim($input->getOption('path'), '/') . '/';

        // Files array
        $files = [
            'Brand'            => 'ProaxiveLite-Export_Brand.csv',
            'Customer'         => 'ProaxiveLite-Export_Client.csv',
            'Equipment'        => 'ProaxiveLite-Export_Equipment.csv',
            'EquipmentType'    => 'ProaxiveLite-Export_CategoryEquipment.csv',
            'Intervention'     => 'ProaxiveLite-Export_Intervention.csv',
            'OperatingSystem'  => 'ProaxiveLite-Export_OperatingSystem.csv',
        ];

        $io->title('🚀 Import des données Proaxive v1.5.7');
        if ($dryRun) {
            $io->note('Mode DRY-RUN activé : aucun enregistrement ne sera inséré.');
        }

        $missing = [];
        foreach ($files as $label => $filename) {
            if (!file_exists($path . $filename)) {
                $missing[] = $filename;
            }
        }

        if (!empty($missing)) {
            $io->error('Fichiers manquants : ' . implode(', ', $missing));
            return Command::FAILURE;
        }


        $io->success('Tous les fichiers sont présents. Début de l’import…');
        $this->logger->info("Début de l'import CSV V1 depuis CLI");

        $results = [];

        // Brand
        $io->section('📦 Import des marques');
        $brandImporter = new BrandImporter($this->logger, $this->query, $path . $files['Brand'], $dryRun);
        $results['Brand'] = $brandImporter->import();

        // Equipment Type
        $io->section('📁 Import des types d’équipement');
        $equipmentTypeImporter = new EquipmentTypeImporter($this->logger, $this->query, $path . $files['EquipmentType'], $dryRun);
        $results['EquipmentType'] = $equipmentTypeImporter->import();

        // OS
        $io->section('💽 Import des systèmes d’exploitation');
        $operatingSystemImporter = new OperatingSystemImporter($this->logger, $this->query, $path . $files['OperatingSystem'], $dryRun);
        $results['OperatingSystem'] = $operatingSystemImporter->import();

        // Customer
        $io->section('👤 Import des clients');
        $customerImporter = new CustomerImporter($this->logger, $this->query, $path . $files['Customer'], $dryRun);
        $results['Customer'] = $customerImporter->import();

        // Equipment
        $io->section('🖥️ Import des équipements');
        $equipmentImporter = new EquipmentImporter(
            $this->logger,
            $this->query,
            $path . $files['Equipment'],
            $dryRun,
            $brandImporter->brandMap,
            $equipmentTypeImporter->equipmentType,
            $operatingSystemImporter->operatingSystem,
            $customerImporter->customerMap
        );
        $results['Equipment'] = $equipmentImporter->import();

        // Interventions
        $io->section('🧾 Import des interventions');
        $interventionImporter = new InterventionImporter(
            $this->logger,
            $this->query,
            $path . $files['Intervention'],
            $dryRun,
            $equipmentImporter->equipmentMap,
            $customerImporter->customerMap
        );
        $results['Intervention'] = $interventionImporter->import();

        // Résumé final
        $io->newLine(2);
        $io->title('📊 Résumé de l’import');
        $io->table(
            ['Entité', 'Importés', 'Ignorés', 'Erreurs'],
            array_map(fn($key, $res) => [$key, $res['imported'], $res['skipped'], $res['errors']], array_keys($results), $results)
        );

        $io->success('Importation terminée' . ($dryRun ? ' (simulation)' : '') . ' 🎉');

        return Command::SUCCESS;
    }

}