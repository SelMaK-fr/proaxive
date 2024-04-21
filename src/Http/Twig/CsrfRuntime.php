<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Twig;

use Exception;
use Slim\Csrf\Guard;

class CsrfRuntime
{
    /**
     * @var Guard
     */
    protected Guard $csrf;

    /**
     * @param Guard $csrf The csrf
     */
    public function __construct(Guard $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function csrf(): string
    {
        return '
            <input type="hidden" name="' . $this->csrf->getTokenNameKey() . '" value="' .
            $this->csrf->getTokenName() . '">
            <input type="hidden" name="' . $this->csrf->getTokenValueKey() . '" value="' .
            $this->csrf->getTokenValue() . '">';
    }
}