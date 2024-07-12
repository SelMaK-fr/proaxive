<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Storage;

use Slim\Psr7\UploadedFile;

interface UploadInterface
{
    public function move($directory, UploadedFile $uploadedFile): string;
}