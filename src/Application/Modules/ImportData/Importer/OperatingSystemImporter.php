<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Application\Modules\ImportData\Importer;

use Selmak\Proaxive2\Infrastructure\Import\AbstractImporter;

class OperatingSystemImporter extends AbstractImporter
{
    public array $operatingSystem = [];

    public function import(): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $skippedRows = [];

        foreach ($this->readCsv() as $row) {
            $oldId = (int) $row['id'];
            $name = trim($row['os_name']);

            if ($name === '') {
                $this->logger->warning("Ligne ignorée (nom vide) : " . json_encode($row));
                $skipped++;
                $skippedRows[] = $row;
                continue;
            }


            $existing = $this->pdo->from('operating_systems')->where('os_name', $row['os_name'])->fetch();

            if ($existing) {
                $this->operatingSystem[$oldId] = $existing['id'];
                $skipped++;
                continue;
            }

            if ($this->isDryRun()) {
                $this->logger->info("[DryRun] OS à insérer : $name");
                $imported++;
                continue;
            }

            try {
                $this->pdo->insertInto('operating_systems')->values([
                    'os_name' => $row['os_name'],
                    'os_release' => $row['os_release'],
                    'os_architecture' => $row['os_architecture'],
                    'os_about' => $row['os_about'],
                    'os_full' => $row['os_full'],
                    'v1_id' => (int)$row['id'],
                ])->execute();
                $newId = $this->pdo->getPdo()->lastInsertId();
                $this->operatingSystem[$oldId] = $newId;
            } catch (\Exception $e){
                $this->logger->error("Erreur à l'import du système d'exploitation ID=$oldId ($name) : " . $e->getMessage());
                $errors++;
                $skippedRows[] = $row;
            }
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
            'skipped_rows' => $skippedRows,
        ];
    }
}