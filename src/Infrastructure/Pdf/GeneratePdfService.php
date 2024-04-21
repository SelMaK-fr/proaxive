<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Infrastructure\Pdf;

use Knp\Snappy\Pdf;
use Slim\App;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GeneratePdfService implements GeneratePdfInterface
{

    public function __construct(private readonly App $app, protected readonly Twig $twig)
    {
    }

    /**
     * Context -> Filename -> template Twig
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function createPdf(array $context, string $filename, string $templateTwig): bool
    {
        $settings = $this->app->getContainer()->get('settings');
        $snappy = new Pdf();
        $snappy->setBinary($settings['settings']['rootPath'] . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $snappy->generateFromHtml($this->twig->fetch($templateTwig,
            ['context' => $context,
            ]), $filename);
        return true;
    }
}