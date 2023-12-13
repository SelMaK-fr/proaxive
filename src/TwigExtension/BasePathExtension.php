<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BasePathExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getBaseUrl', [$this, 'getBaseUrl'], ['is_safe' => ['html']]),
        ];
    }

    public function getBaseUrl(): string
    {
        // output: /myproject/index.php
        $currentPath = $_SERVER['PHP_SELF'];

        // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
        $pathInfo = pathinfo($currentPath);

        // output: localhost
        $hostName = $_SERVER['HTTP_HOST'];

        // output: http://
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

        // return: http://localhost/myproject/
        return $protocol.'://'.$hostName;
    }
}