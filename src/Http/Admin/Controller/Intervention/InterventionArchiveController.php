<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use DI\NotFoundException;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\Exception\HttpNotFoundException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionArchiveController extends AbstractController
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
    public function index(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
        if(!$i){
            throw new HttpNotFoundException($request, 'This intervention could not be found. Please check your database.');
        }
        if($request->getMethod() === 'POST'){
            $data = [
              'state' => 'ARCHIVE'
            ];
            $save = $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            if($save){
                $this->addFlash('panel-success', sprintf("L'intervention n°%s a bien été archivée.", $i->ref_number));
                return $this->redirectToRoute('intervention_archive_read', ['id' => $intervention_id]);
            }
        }
        return $this->render($response, 'backoffice/intervention/archive.html.twig', [
            'i' => $i,
            'currentMenu' => 'intervention'
        ]);
    }

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
    public function readArchive(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
        if(!$i){
            throw new HttpNotFoundException($request, 'This intervention could not be found. Please check your database.');
        }
        return $this->render($response, 'backoffice/intervention/read_archive.html.twig', [
            'i' => $i,
            'currentMenu' => 'intervention',
            'intervention_id' => $intervention_id
        ]);
    }

}