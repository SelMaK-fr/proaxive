<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Pdf;

interface GeneratePdfInterface
{
    public function createPdf(array $context, string $filename, string $templateTwig):bool;
}