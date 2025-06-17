<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Application\Modules\ImportData\Importer;

use DateTimeImmutable;
use Envms\FluentPDO\Query;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Selmak\Proaxive2\Infrastructure\Import\AbstractImporter;

class EquipmentImporter extends AbstractImporter
{
    public array $equipmentMap = [];


    public function __construct(
        protected LoggerInterface $logger,
        Query $pdo,
        string $csvPath,
        bool $dryRun,
        private array $brandMap,
        private array $equipmentTypeMap,
        private array $osMap,
        private array $customerMap
    ) {
        parent::__construct($logger, $pdo, $csvPath, $dryRun);
    }

    public function import(): array
    {
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        $skippedRows = [];

        foreach ($this->readCsv() as $row) {
            $oldId = (int) $row['id'];
            $name = trim($row['name']);

            // Skip si une relation est manquante
            if (
                !isset($this->brandMap[(int)$row['brand_id']]) ||
                !isset($this->equipmentTypeMap[(int)$row['category_equipment_id']]) ||
                !isset($this->osMap[(int)$row['operating_systems_id']]) ||
                !isset($this->customerMap[(int)$row['client_id']])
            ) {
                $this->logger->info(sprintf('⚠️ Skip equipment ID %s : relation manquante', $oldId));
                $skipped++;
                $skippedRows[] = $row;
                continue;
            }

            // Optionnel : vérifier s’il existe déjà par nom + client
            $existing = $this->pdo->from('equipments')
                ->where('name', $name)
                ->where('customers_id', $this->customerMap[(int)$row['client_id']])
                ->fetch();

            if ($existing) {
                $this->logger->info(sprintf('ℹ️ Equipment déjà présent : %s (client ID v2 %s)', $name, $this->customerMap[(int)$row['customer_id']]));
                $skipped++;
                continue;
            }

            $createdAt = self::formatDate($row['created_at']);
            $updatedAt = self::formatDate($row['updated_at']);

            try {
                if ($this->isDryRun()) {
                    $this->logger->info("[DryRun] Equipment à importer : $name");
                    $imported++;
                    continue;
                }
                $this->pdo->insertInto('equipments')->values([
                    'name' => $row['name'],
                    'e_licence' => $row['licence_os'],
                    'u_username' => $row['u_username'],
                    'u_account_mail' => $row['u_account_mail'],
                    'u_password' => $row['u_password'],
                    'u_domain' => $row['u_domain'],
                    'n_ipaddress' => $row['n_ipaddress'],
                    'n_gateway' => $row['n_gateway'],
                    'n_masknetwork' => $row['n_masknetwork'],
                    'n_dns' => $row['n_dns'],
                    'n_ssid' => $row['n_ssid'],
                    'n_wifi_key' => $row['n_wifi_key'],
                    'end_guarantee' => $row['end_guarantee'],
                    'c_install_date' => $row['c_install_date'],
                    'c_processor' => $row['c_processor'],
                    'c_motherboard' => $row['c_motherboard'],
                    'c_socket' => $row['c_socket'],
                    'c_bios' => $row['c_bios'],
                    'c_gpu' => $row['c_gpu'],
                    'c_memory' => $row['c_memory'],
                    'c_hdd0' => $row['c_hdd0'],
                    'c_hdd1' => $row['c_hdd1'],
                    'c_hdd2' => $row['c_hdd2'],
                    'c_hdd3' => $row['c_hdd3'],
                    'c_software' => $row['c_software'],
                    'bao_temp_file' => $row['bao_last_file'],
                    'e_serial' => $row['serialnumber'] ?? null,
                    'brands_id' => $this->brandMap[(int)$row['brand_id']],
                    'types_equipments_id' => $this->equipmentTypeMap[(int)$row['category_equipment_id']],
                    'operating_systems_id' => $this->osMap[(int)$row['operating_systems_id']],
                    'customers_id' => $this->customerMap[(int)$row['client_id']],
                    'customer_name' => $this->getCustomerName($row),
                    'brand_name' => $this->getBrandName($row),
                    'type_name' => $this->getTypeName($row),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                    'v1_id' => $oldId,
                ])->execute();

                $newId = $this->pdo->getPdo()->lastInsertId();
                $this->equipmentMap[(int)$row['id']] = $newId;
                $imported++;
            } catch (\Exception $e){
                $this->logger->error("❌ Erreur import équipement ID $oldId ($name) : " . $e->getMessage());
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

    private function getBrandName(array $row): string
    {
        return $this->pdo->from('brands')
            ->where('id', $this->brandMap[(int)$row['brand_id']])
            ->fetch('name') ?? 'inconnu';
    }

    private function getTypeName(array $row): string
    {
        return $this->pdo->from('types_equipments')
            ->where('id', $this->equipmentTypeMap[(int)$row['category_equipment_id']])
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