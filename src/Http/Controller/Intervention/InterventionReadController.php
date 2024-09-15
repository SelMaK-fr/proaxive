<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Intervention;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Company\Repository\CompanyRespository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\Task\Repository\TaskAssocRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        $ref_for_link = $args['ref_for_link'];
        $i = $this->getRepository(InterventionRepository::class)->findByReferenceLink($ref_for_link);
        $workshop = $this->getRepository(CompanyRespository::class)->find('id', $i->company_id);
        $t = $this->getRepository(TaskAssocRepository::class)->findByIntervention((int)$i->id);
        return $this->render($response, 'frontoffice/intervention/read.html.twig', [
            'i' => $i,
            'tasks' => $t,
            'workshop' => $workshop
        ]);
    }
}