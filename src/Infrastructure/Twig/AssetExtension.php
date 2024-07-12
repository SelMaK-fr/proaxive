<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Infrastructure\Twig;

use Selmak\Proaxive2\Settings\SettingsInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetExtension extends AbstractExtension
{
    public function __construct(private readonly SettingsInterface $settings){}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', $this->getAssetUrl(...)),
            new TwigFunction('assetExist', $this->getFileExist(...)),
        ];
    }

    public function getAssetUrl(string $path): string
    {
        if ($this->isAbsoluteUrl($path)) {
            return $path;
        }
        return $this->settings->get('app')['urlPath'] . $path;
    }

    /**
     * Permet de vérifier si le fichier est présent sur le serveur.
     * Renvoi une image default si null
     * Cette méthode retourne toujours une chaîne de caractère
     * @param string $path
     * @param string $filename
     * @return string
     */
    public function getFileExist(string $path, ?string $filename, ?string $fileNotFound = '226-notFound.png'): string
    {
        if(!empty($filename)){
            $routePath = $this->settings->get('settings')['rootPath'] . '/public';
            $toCheck = $path . '/' . $filename;
            if(!file_exists($routePath . $toCheck))
            {
                return '/img/' . $fileNotFound;
            }
            return $toCheck;
        }
        return '/img/' . $fileNotFound;
    }

    protected function isAbsoluteUrl(string $url): bool
    {
        return str_contains($url, '://') || str_starts_with($url, '//');
    }
}