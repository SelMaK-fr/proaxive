<?php

namespace Selmak\Proaxive2\Infrastructure\Import;

use Envms\FluentPDO\Query;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Selmak\Proaxive2\Domain\Import\ImporterInterface;

abstract class AbstractImporter implements ImporterInterface
{

    protected Query $pdo;
    protected string $csvPath;
    protected LoggerInterface $logger;
    private bool $dryRun;

    public function __construct(LoggerInterface $logger, Query $pdo, string $csvPath, bool $dryRun = false)
    {
        $this->pdo = $pdo;
        $this->csvPath = $csvPath;
        $this->logger = $logger;
        $this->dryRun = $dryRun;
    }

    protected function readCsv(): \Generator
    {
        if (!file_exists($this->csvPath)) {
            throw new InvalidArgumentException("Fichier CSV introuvable : $this->csvPath");
        }

        if (($handle = fopen($this->csvPath, 'r')) === false) {
            throw new RuntimeException("Impossible d'ouvrir le fichier : $this->csvPath");
        }

        // Lire la ligne d'en-tête
        $header = fgetcsv($handle, 0, ';');

        if (!$header) {
            throw new RuntimeException("Fichier CSV vide ou entête illisible : $this->csvPath");
        }

        // Supprimer le BOM UTF-8 de la première colonne si présent
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        // Lire les lignes suivantes
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) !== count($header)) {
                // Optionnel : ignorer les lignes corrompues
                continue;
            }
            yield array_combine($header, $row);
        }

        fclose($handle);
    }

    protected function isDryRun(): bool
    {
        return $this->dryRun;
    }

}