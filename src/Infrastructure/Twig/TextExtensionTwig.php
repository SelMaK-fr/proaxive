<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extensions pour les textes
 */
class TextExtensionTwig extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('excerpt', [$this, 'excerpt']),
        ];
    }

    /**
     * Retourne un extrait du texte.
     * @param string $text
     * @param int $limit
     * @param string $end
     * @return string
     */
    public function excerpt(string $text, int $limit = 200, string $end = '...'): string
    {
        if(mb_strlen($text) > $limit) {
            $excerpt = mb_substr($text, 0, $limit);
            $lastSpace = mb_strrpos($excerpt, ' ');
            return mb_substr($excerpt, 0, $lastSpace) . $end;
        }
        return $text;
    }
}