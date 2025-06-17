<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Application\Modules\ImportData\Importer;

use Selmak\Proaxive2\Infrastructure\Import\AbstractImporter;

class EquipmentTypeImporter extends AbstractImporter
{
    public array $equipmentType = [];

    public function import(): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $skippedRows = [];

        foreach ($this->readCsv() as $row) {
            $oldId = (int) $row['id'];
            $name = trim($row['title']);

            if ($name === '') {
                $this->logger->warning("Ligne ignorée (nom vide) : " . json_encode($row));
                $skipped++;
                $skippedRows[] = $row;
                continue;
            }

            $existing = $this->pdo->from('types_equipments')->where('name', $row['title'])->fetch();

            if ($existing) {
                $this->equipmentType[$oldId] = $existing['id'];
                $skipped++;
                continue;
            }

            if ($this->isDryRun()) {
                $this->logger->info("[DryRun] Equipement à insérer : $name");
                $imported++;
                continue;
            }

            try {
                $this->pdo->insertInto('types_equipments')->values([
                    'name' => $row['title'],
                    'slug' => $row['slug'],
                    'v1_id' => (int)$row['id'],
                ])->execute();
                $newId = $this->pdo->getPdo()->lastInsertId();
                $this->equipmentType[$oldId] = $newId;
            } catch (\Exception $e){
                $this->logger->error("Erreur à l'import de la catégorie ID=$oldId ($name) : " . $e->getMessage());
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