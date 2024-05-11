<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment;

use Awurth\Validator\StatefulValidator;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EquipmentAjaxController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function search(Request $request, Response $response): Response
    {
        $e = [];
        if($request->getMethod() === 'GET') {
            $e = $this->getRepository(EquipmentRepository::class)->search($request->getAttribute('key'));
        }
        return $this->render($response, 'backoffice/equipment/search.html.twig', [
            'equipments' => $e,
            'currentMenu' => 'equipment'
        ]);
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function addFast(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
            $data = $request->getParsedBody()['form_equipment'];
            $validator = $this->validator->validate($data, [
                'name' => [
                    'rules' => v::notBlank(),
                    'messages' => [
                        'notBlank' => "Veuillez renseigner un titre"
                    ]
                ],
                'types_equipments_id' => [
                    'rules' => v::notBlank(),
                    'messages' => [
                        'notBlank' => "Veuillez sélectionner un type"
                    ]
                ]
            ]);
            if($validator->count() === 0) {
                $save = $this->getRepository(EquipmentRepository::class)->add($data, true);
                if($save) {
                    $this->addFlash('panel-info', sprintf("L'équipement - %s - a bien été créé", $data['name']));
                    return $this->redirectToReferer($request);
                }
            } else {
                $this->addFlash('panel-error', "Des champs ne sont pas remplis");
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToReferer($request);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function updateNote(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST'){
            $customer_id = (int)$args['id'];
            $data = $request->getParsedBody();
            $validator = $this->validator->validate($request, [
                'about' => [
                    'rules' => V::length(8,200)
                ]
            ]);
            if($validator->count() === 0){
                $this->getRepository(EquipmentRepository::class)->update($data, $customer_id);
            }
        }
        return $response;
    }
}