<?php

namespace Selmak\Proaxive2\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_env', [$this, 'getEnvironmentVariable']),
        ];
    }

    /**
     * Return the value of the requested environment variable.
     *
     * @param String $varname
     * @return String
     */
    public function getEnvironmentVariable($varname)
    {
        return $_ENV[$varname];
    }
}