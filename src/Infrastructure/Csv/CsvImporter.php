<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Csv;

use League\Csv\Reader;

final class CsvImporter
{
    public function __construct(
        private string $filePath,
        private string $delimiter = ';',
    ) {}

    /**
     * @return iterable<array<string, string>>
     */
    public function getRecords(): iterable
    {
        $csv = Reader::createFromPath($this->filePath, 'r');
        $csv->setDelimiter($this->delimiter);
        $csv->setHeaderOffset(0);

        return $csv->getRecords();
    }

    public function getHeaders(): array
    {
        $csv = Reader::createFromPath($this->filePath, 'r');
        $csv->setDelimiter($this->delimiter);
        $csv->setHeaderOffset(0);

        return $csv->getHeader();
    }
}