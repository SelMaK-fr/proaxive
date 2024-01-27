<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionArchiveController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);
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
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function readArchive(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->find('id', $intervention_id);

        return $this->render($response, 'backoffice/intervention/read_archive.html.twig', [
            'i' => $i,
            'currentMenu' => 'intervention'
        ]);
    }

}