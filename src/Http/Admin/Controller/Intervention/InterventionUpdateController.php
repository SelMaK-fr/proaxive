<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Intervention;

use Creitive\Breadcrumbs\Breadcrumbs;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\EDocument\Repository\EDocumentRepository;
use Selmak\Proaxive2\Domain\Intervention\Intervention;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Domain\InterventionPicture\Repository\InterventionPictureRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Intervention\InterventionUpdateType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class InterventionUpdateController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function observations(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
        if(!$i) {
            $this->addFlash('panel-error', "Cette intervention n'existe pas !");
            return $this->redirectToRoute('dash_intervention');
        }
        // Form Update Intervention
        $form = $this->createForm(InterventionUpdateType::class, $i);
        $form->setAction($this->getUrlFor('intervention_update', ['id' => $intervention_id]));
        $form->handleRequest();

        if($request->getMethod() === 'POST') {
            $data = $form->getRequestData()['form-intervention_update'];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            $this->addFlash('panel-success', sprintf("L'intervention - %s - a bien été mise à jour", $data['name']));
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($i['customer_name'], $this->getUrlFor('customer_read', ['id' => $i['customers_id']]));
        $bds->addCrumb($i['equipment_name'], $this->getUrlFor('equipment_read', ['id' => $i['equipments_id']]));
        $bds->addCrumb($i['ref_number'], false);
        $bds->addCrumb('Observations', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/update_notes.html.twig', [
            'currentMenu' => 'intervention',
            'i' => $i,
            'form' => $form,
            'breadcrumbs' => $bds
        ]);
    }

    public function files(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
        if(!$i) {
            $this->addFlash('panel-error', "Cette intervention n'existe pas !");
            return $this->redirectToRoute('dash_intervention');
        }
        // Find Documents
        $documents = $this->getRepository(EDocumentRepository::class)->allBy('interventions_id', $intervention_id);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($i['customer_name'], $this->getUrlFor('customer_read', ['id' => $i['customers_id']]));
        $bds->addCrumb($i['equipment_name'], $this->getUrlFor('equipment_read', ['id' => $i['equipments_id']]));
        $bds->addCrumb($i['ref_number'], false);
        $bds->addCrumb('Fichiers joints', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/documents.html.twig', [
            'currentMenu' => 'intervention',
            'i' => $i,
            'documents' => $documents,
            'breadcrumbs' => $bds
        ]);
    }

    /*
     * Gestion de la galerie photos
     */
    public function gallery(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        $i = $this->getRepository(InterventionRepository::class)->joinForId($intervention_id);
        if(!$i) {
            $this->addFlash('panel-error', "Cette intervention n'existe pas !");
            return $this->redirectToRoute('dash_intervention');
        }
        // Find all picture for ID intervention
        $pictures = $this->getRepository(InterventionPictureRepository::class)->allBy('interventions_id', $intervention_id);
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Interventions', $this->getUrlFor('dash_intervention'));
        $bds->addCrumb($i['customer_name'], $this->getUrlFor('customer_read', ['id' => $i['customers_id']]));
        $bds->addCrumb($i['equipment_name'], $this->getUrlFor('equipment_read', ['id' => $i['equipments_id']]));
        $bds->addCrumb($i['ref_number'], false);
        $bds->addCrumb('Galerie', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/intervention/gallery.html.twig', [
            'currentMenu' => 'intervention',
            'i' => $i,
            'pictures' => $pictures,
            'breadcrumbs' => $bds
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $intervention_id = (int)$args['id'];
        if($request->getMethod() === 'POST') {
            $data = array_values($request->getParsedBody())[0];
            $this->getRepository(InterventionRepository::class)->update($data, $intervention_id);
            $this->addFlash('panel-success', sprintf("L'intervention - %s - a bien été mise à jour", $data['name']));
            return $this->redirectToRoute('intervention_read', ['id' => $intervention_id]);
        }
        return new \Slim\Psr7\Response();
    }
}