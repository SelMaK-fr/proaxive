<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\TwigExtension;

use Selmak\Proaxive2\Runtime\CsrfRuntime;
use Slim\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigCsrfExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf', [$this, 'csrfTwig'], ['is_safe' => ['html']]),
        ];
    }

    public function csrfTwig(): string
    {

        $lastKey = array_key_last($_SESSION['csrf']);
        $lastValue = $_SESSION['csrf'][$lastKey];

        return "
            <input type='hidden' name='csrf_name' value='". $lastKey ."'>
            <input type='hidden' name='csrf_value' value='". $lastValue ."'>
        ";
    }
}