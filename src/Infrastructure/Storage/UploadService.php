<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Storage;

use Slim\Psr7\UploadedFile;

class UploadService implements UploadInterface
{
    const PERMESSIONS = 0777;
    public function move($directory, UploadedFile $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        if (!file_exists($directory)) {
            mkdir($directory, self::PERMESSIONS, true);
        }
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}