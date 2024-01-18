<?php
declare(strict_types=1);
namespace App\Interface;

interface GeneratePdfInterface
{
    public function createPdf(array $context, string $filename, string $templateTwig):bool;
}