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
        ];
    }

    public function getAssetUrl(string $path): string
    {
        if ($this->isAbsoluteUrl($path)) {
            return $path;
        }
        return $this->settings->get('app')['urlPath'] . $path;
    }

    protected function isAbsoluteUrl(string $url): bool
    {
        return str_contains($url, '://') || str_starts_with($url, '//');
    }
}