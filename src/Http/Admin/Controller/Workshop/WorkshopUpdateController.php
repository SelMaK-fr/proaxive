<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Admin\Controller\Workshop;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Selmak\Proaxive2\Domain\Workshop\Repository\WorkshopRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Http\Type\Admin\Workshop\WorkshopUploadLogoType;
use Selmak\Proaxive2\Http\Type\Admin\Workshop\WorkshopUploadSignatureType;
use Selmak\Proaxive2\Http\Type\Admin\WorkshopType;
use Sirius\Upload\Handler;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WorkshopUpdateController extends AbstractController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws RuntimeError
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $workshop_id = (int)$args['id'];
        $workshop = $this->getRepository(WorkshopRepository::class)->find('id', $workshop_id);

        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_workshop'];
            $saveUpdate = $this->getRepository(WorkshopRepository::class)->update($data, $workshop_id);
            if($saveUpdate) {
                $this->addFlash('panel-info', sprintf('Mise à jour pour - %s - effectuée', $data['name']));
            }
        }

        // Form for Logo
        $uploadLogo = $this->createForm(WorkshopUploadLogoType::class, $workshop);
        $uploadLogo->setAction($this->getUrlFor('workshop_update_logo', ['id' => $workshop_id]));
        $uploadLogo->handleRequest();

        // Form for Signature
        $uploadSignature = $this->createForm(WorkshopUploadSignatureType::class, $workshop);
        $uploadSignature->setAction($this->getUrlFor('workshop_update_signature', ['id' => $workshop_id]));
        $uploadSignature->handleRequest();

        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Magasins', $this->getUrlFor('dash_workshop'));
        $bds->addCrumb($workshop->name, false);
        $bds->addCrumb('Modification', false);
        $bds->render();
        // .Breadcrumbs

        return $this->render($response, 'backoffice/workshop/update.html.twig', [
            'form' => $form,
            'uploadLogo' => $uploadLogo,
            'uploadSignature' => $uploadSignature,
            'breadcrumbs' => $bds,
            'workshop' => $workshop,
            'currentMenu' => 'workshop'
        ]);
    }

}