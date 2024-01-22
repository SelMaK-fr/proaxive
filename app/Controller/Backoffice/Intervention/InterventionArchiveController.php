<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Intervention;

use App\AbstractController;
use App\Repository\InterventionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterventionArchiveController extends AbstractController
{

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
                $this->session->getFlash()->add('panel-success', sprintf("L'intervention n°%s a bien été archivée.", $i->ref_number));
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
     * @throws \Envms\FluentPDO\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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