<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Application\Modules\ImportData\Importer;

use DateTime;
use DateTimeImmutable;
use RuntimeException;
use Selmak\Proaxive2\Infrastructure\Import\AbstractImporter;

class CustomerImporter extends AbstractImporter
{

    public array $customerMap = [];

    public function import(): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $skippedRows = [];

        foreach ($this->readCsv() as $row) {
            $oldId = (int) $row['id'];
            $name = trim($row['fullname']);

            if ($name === '') {
                $this->logger->warning("Ligne ignorée (nom vide) : " . json_encode($row));
                $skipped++;
                $skippedRows[] = $row;
                continue;
            }

            $existing = $this->pdo->from('customers')->where('mail', $row['mail'])->fetch();

            if ($existing) {
                $this->customerMap[(int)$row['id']] = $existing['id'];
                $skipped++;
                continue;
            }

            if ($this->isDryRun()) {
                $this->logger->info("[DryRun] Client à insérer : $name");
                $imported++;
                continue;
            }

            try {
                $rawDate = trim($row['created_at']);
                $format = 'Y-m-d H:i:s';
                $dateCreated = DateTimeImmutable::createFromFormat($format, $row['created_at']);
                $dateUpdated = DateTimeImmutable::createFromFormat($format, $row['updated_at']);

                if (!$dateCreated || $dateCreated->format('Y-m-d H:i:s') !== $rawDate) {
                    throw new RuntimeException("❌ Date invalide ou mal formatée : '$rawDate'");
                }
                $this->pdo->insertInto('customers')->values([
                    'lastname' => $row['lastname'],
                    'firstname' => $row['firstname'],
                    'fullname' => $row['fullname'],
                    'phone' => $row['phone'],
                    'mobile' => $row['mobile'],
                    'address' => $row['adress'],
                    'zipcode' => $row['zipcode'],
                    'city' => $row['city'],
                    'created_at' => $dateCreated->format('Y-m-d H:i:s'),
                    'updated_at' => $dateUpdated->format('Y-m-d H:i:s'),
                    'activated' => 0,
                    'mail' => $row['mail'],
                    'v1_id' => $row['id'],
                ])->execute();
                $newId = $this->pdo->getPdo()->lastInsertId();
                $this->customerMap[$oldId] = $newId;
                $imported++;
            } catch (\Exception $e){
                $this->logger->error("Erreur à l'import du client ID=$oldId ($name) : " . $e->getMessage());
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

    private function parseCsvDate(string $raw): ?string {
        $raw = trim($raw);
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $raw);

        if (!$dt || $dt->format('Y-m-d H:i:s') !== $raw) {
            return null; // ou fallback
        }

        return $dt->format('Y-m-d H:i:s');
    }
}