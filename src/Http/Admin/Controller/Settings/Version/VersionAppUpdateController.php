<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings\Version;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class VersionAppUpdateController extends AbstractController
{
    public function __invoke(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
            $application = new Application('Proaxive', '2.0.x');

            // Create command
            $application->setAutoExit(false);
            $input = new ArrayInput([
                'command' => 'symfony:version:update',
            ]);
            $output = new BufferedOutput();
            $application->run($input, $output);

            // Return result
            $content = $output->fetch();

            return new JsonResponse($content);
        }
    }
}