<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Command;

use Envms\FluentPDO\Query;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModifyInterventionsForCompleted extends Command
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Query $query
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        $this->setName('modify:intervention:completed');
        $this->setDescription('Update Intervention if Completed (fail 1.5.7 import)');
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Effectuer un test sans insérer en base');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $success = 0;
        $errors = 0;
        // Symfony Console Style
        $io = new SymfonyStyle($input, $output);

        // Options
        $dryRun = $input->getOption('dry-run');

        $io->title('🚀 Recherche des interventions cloturées');
        if ($dryRun) {
            $io->note('Mode DRY-RUN activé : aucun enregistrement ne sera inséré.');
        }

        $interventions = $this->query->from('interventions')->where('is_closed = 1')->fetchAll();

        foreach ($interventions as $i) {
            try {
                $update = $this->query->update('interventions', [
                    'way_steps' => 5,
                    'state' => 'COMPLETED',
                    'status_id' => 4
                ], (int)$i['id']);
                $update->execute();
                $success++;
            } catch (\Exception $e) {
                $this->logger->error("Erreur : " . $e->getMessage());
                $errors++;
            }

        }

        // Résumé final
        $io->newLine(2);
        $io->title('📊 Résumé de la mise à jour');
        $io->info('Intervention(s) traitée(s) : ' . $success);

        $io->success('Mise à jour terminée' . ($dryRun ? ' (simulation)' : '') . ' 🎉');

        return Command::SUCCESS;
    }
}