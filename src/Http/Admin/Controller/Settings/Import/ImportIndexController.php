<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Settings\Import;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\BrandImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\CustomerImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\EquipmentImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\EquipmentTypeImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\InterventionImporter;
use Selmak\Proaxive2\Application\Modules\ImportData\Importer\OperatingSystemImporter;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class ImportIndexController extends AbstractController
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $flash = null;
        $basePath = $this->settings->get('storage')['app'] . 'imports/';

        $files = [
            'Brand' => 'ProaxiveLite-Export_Brand.csv',
            'Customer' => 'ProaxiveLite-Export_Client.csv',
            'Equipment' => 'ProaxiveLite-Export_Equipment.csv',
            'EquipmentType' => 'ProaxiveLite-Export_CategoryEquipment.csv',
            'Intervention' => 'ProaxiveLite-Export_Intervention.csv',
            'OperatingSystem' => 'ProaxiveLite-Export_OperatingSystem.csv',
        ];

        $missingFiles = [];

        foreach ($files as $label => $filename) {
            $fullPath = $basePath . $filename;
            if (!file_exists($fullPath)) {
                $missingFiles[] = $filename;
            }
        }

        if (!empty($missingFiles)) {
            $flash = 'Les fichiers suivants sont manquants et doivent être présents pour continuer : ' . implode(', ', $missingFiles);
        }
        return $this->render($response, 'backoffice/settings/import/index.html.twig', [
            'missingFiles' => $missingFiles,
            'flash' => $flash,
        ]);
    }

    public function importData(ServerRequestInterface $request): ResponseInterface
    {
        if($request->getMethod() === 'POST') {
            // Import Brand
            $brandImporter = new BrandImporter(
                $this->logger,
                $this->query,
                $this->settings->get('storage')['app'] . 'imports/ProaxiveLite-Export_Brand.csv'
            );
            $brandImporter->import();
            // Import EquipmentType
            $equipmentTypeImporter = new EquipmentTypeImporter(
                $this->logger,
                $this->query,
                $this->settings->get('storage')['app'] . 'imports/ProaxiveLite-Export_CategoryEquipment.csv');
            $equipmentTypeImporter->import();
            // Operating System
            $operatingSystemImporter = new OperatingSystemImporter(
                $this->logger,
                $this->query,
                $this->settings->get('storage')['app'] . 'imports/ProaxiveLite-Export_OperatingSystem.csv');
            $operatingSystemImporter->import();
            // Customers
            $customerImporter = new CustomerImporter(
                $this->logger,
                $this->query,
                $this->settings->get('storage')['app'] . 'imports/ProaxiveLite-Export_Client.csv');
            $customerImporter->import();

            // Import with relation ID
            // Equipment
            $equipmentImporter = new EquipmentImporter(
                $this->logger,
                $this->query,
                $this->settings->get('storage')['app'] . 'imports/ProaxiveLite-Export_Equipment.csv',
                false,
                $brandImporter->brandMap,
                $equipmentTypeImporter->equipmentType,
                $operatingSystemImporter->operatingSystem,
                $customerImporter->customerMap
            );
            $equipmentImporter->import();
            // Interventions
            $interventionImporter = new InterventionImporter(
                $this->logger,
                $this->query,
                $this->settings->get('storage')['app'] . 'imports/ProaxiveLite-Export_Intervention.csv',
                false,
                $equipmentImporter->equipmentMap,
                $customerImporter->customerMap
            );
            $interventionImporter->import();

            // If Success
            $this->addFlash('panel-success', 'Import successful');
            return $this->redirectToRoute('settings_preference');
        }
        return $this->redirectToRoute('settings_preference');
    }
}