<?php

namespace Selmak\Proaxive2\Http\Admin\Controller\Equipment\PeripheralDevice;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Equipment\Repository\EquipmentRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\PeripheralType;

class PeripheralUpdateController extends AbstractController
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
    public function update(Request $request, Response $response, array $args): Response
    {
        $equipment_id = (int)$args['id'];
        $e = $this->getRepository(EquipmentRepository::class)->find('id', $equipment_id, true);
        $form = $this->createForm(PeripheralType::class, $e);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_equipment'];
            $save = $this->getRepository(EquipmentRepository::class)->update($data, $equipment_id);
            if($save) {
                $this->addFlash('panel-info', sprintf("Le périphérique - %s - a bien été mis à jour.", $data['name']));
                return $this->redirectToReferer($request);
            }
        }
        // Breadcrumbs
        $bds = $this->app->getContainer()->get('breadcrumbs');
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Équipements', $this->getUrlFor('dash_equipment'));
        $bds->addCrumb('Périphérique', false);
        $bds->addCrumb($e->name, false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/equipment/device/update.html.twig', [
            'form' => $form,
            'e' => $e,
            'breadcrumbs' => $bds,
            'currentMenu' => 'equipment'
        ]);
    }
}