<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Application\Modules\ImportData\Importer;

use DateTimeImmutable;
use Envms\FluentPDO\Query;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Selmak\Proaxive2\Infrastructure\Import\AbstractImporter;

class InterventionImporter extends AbstractImporter
{
    public function __construct(
        protected LoggerInterface $logger,
        Query $pdo,
        string $csvPath,
        bool $dryRun,
        private array $equipmentMap,
        private array $customerMap
    ) {
        parent::__construct($logger,$pdo, $csvPath, $dryRun);
    }

    public function import(): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $skippedRows = [];

        foreach ($this->readCsv() as $row) {
            $v1Id = (int) $row['id'];
            $name = trim($row['title']);
            // On skip si une relation est manquante
            if(
                !isset($this->customerMap[(int)$row['client_id']]) ||
                !isset($this->equipmentMap[(int)$row['equipment_id']])
            ) {
                $this->logger->info(sprintf('⚠️ Skip intervention ID %s : relation manquante', $v1Id));
                $skipped++;
                $skippedRows[] = $row;
                continue;
            }

            // Recréation des dates et du format
            $createdAt = self::formatDate($row['created_at']);
            $updatedAt = self::formatDate($row['updated_at']);
            $endDate = null;

            try {
                // Si la date de démarrage est vide, on créer une date du jour
                if(!$row['start']) {
                    $startDate = new DateTimeImmutable();
                } else {
                    $startDate = self::formatDate($row['start']);
                }
                if($row['close_date']) {
                    $endDate = self::formatDate($row['close_date']);
                }

                // Range l'intervention suivant son status. Si inferieur à 7
                // elle est en cours de traitement
                if($row['status_id'] <= 7){
                    $state = 'PROGRESS';
                } else {
                    $state = 'PENDING';
                }

                // Gestion du statut v1 -> v2
                $map = [
                    1 => 1,
                    2 => 3,
                    3 => 2,
                    4 => 3,
                    5 => 5,
                    6 => 1,
                    7 => 5,
                    8 => 4,
                    9 => 2,
                ];
                $status = $map[$row['status_id']] ?? 3;
                // Gestion des étapes

                $this->pdo->insertInto('interventions')->values([
                    'name' => $row['title'],
                    'sort' => $row['type_inter'],
                    'way_steps' => (int)$row['steps'],
                    'ref_number' => $row['number'],
                    'ref_for_link' => $row['number_link'],
                    'state' => $state,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'observation' => $row['observation'],
                    'description' => $row['description'],
                    'note_technician' => $row['note_tech'],
                    'actions_list' => $row['details'],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'is_closed' => (int)$row['closed'],
                    'is_remote' => $row['pmad'],
                    'message_report' => $row['report'],
                    'customer_name' => $this->getCustomerName($row),
                    'equipment_name' => $this->getEquipmentName($row),
                    // IDs
                    // Remettre à 1 en prod
                    'company_id' => 1,
                    'users_id' => 1,
                    'equipments_id' => $this->equipmentMap[(int)$row['equipment_id']],
                    'customers_id' => $this->customerMap[(int)$row['client_id']],
                    'status_id' => $status,
                    'v1_id' => $v1Id,
                ])->execute();
                $imported++;
            } catch (\Exception $e){
                $this->logger->error("❌ Erreur import intervention ID $v1Id ($name) : " . $e->getMessage());
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

    private function getCustomerName(array $row): string
    {
        return $this->pdo->from('customers')
            ->where('id', $this->customerMap[(int)$row['client_id']])
            ->fetch('fullname') ?? 'inconnu';
    }

    private function getEquipmentName(array $row): string
    {
        return $this->pdo->from('equipments')
            ->where('id', $this->equipmentMap[(int)$row['equipment_id']])
            ->fetch('name') ?? 'inconnu';
    }

    private function formatDate(string $fieldDate): string
    {
        $rawDate = trim($fieldDate);
        $format = 'Y-m-d H:i:s';
        $date = DateTimeImmutable::createFromFormat($format, $rawDate);
        if (!$date || $date->format('Y-m-d H:i:s') !== $rawDate) {
            throw new RuntimeException("❌ Date invalide ou mal formatée : '$rawDate'");
        }
        return $date->format($format);
    }
}