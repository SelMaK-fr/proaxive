<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Application\Modules\ImportData\Importer;

use Selmak\Proaxive2\Infrastructure\Import\AbstractImporter;

class BrandImporter extends AbstractImporter
{
    public array $brandMap = []; // v1_id => id_v2

    public function import(): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $skippedRows = [];

        foreach ($this->readCsv() as $row) {
            $oldId = (int) $row['id'];
            $name = trim($row['b_title']);

            if ($name === '') {
                $this->logger->warning("Ligne ignorée (nom vide) : " . json_encode($row));
                $skipped++;
                $skippedRows[] = $row;
                continue;
            }

            $existing = $this->pdo->from('brands')->where('name', $row['b_title'])->fetch();

            if ($existing) {
                $this->brandMap[$oldId] = $existing['id'];
                $skipped++;
                continue;
            }

            if ($this->isDryRun()) {
                $this->logger->info("[DryRun] Marque à insérer : $name");
                $imported++;
                continue;
            }

            try {
                $insertId = $this->pdo->insertInto('brands')->values([
                    'name' => $row['b_title'],
                    'slug' => $row['b_slug'],
                    'logo_file' => $row['b_image'],
                    'v1_id' => $oldId,
                ])->execute();
                $this->brandMap[$oldId] = $insertId;
                $imported++;
            } catch (\Exception $e) {
                $this->logger->error("Erreur à l'import de la marque ID=$oldId ($name) : " . $e->getMessage());
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