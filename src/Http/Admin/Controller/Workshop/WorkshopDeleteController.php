<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Workshop;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Company\Repository\CompanyRespository;
use Selmak\Proaxive2\Domain\Deposit\Repository\DepositRepository;
use Selmak\Proaxive2\Domain\Intervention\Repository\InterventionRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\Workshop\WorkshopDeleteType;
use Slim\Exception\HttpNotFoundException;

class WorkshopDeleteController extends AbstractController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $company_id = (int)$args['id'];
        $c = $this->getRepository(CompanyRespository::class)->find('id', $company_id);
        if(!$c){
            throw new HttpNotFoundException($request, 'This workshop could not be found. Please check your database.');
        }
        $workshopCount = $this->getRepository(CompanyRespository::class)->count();
        $form = $this->createForm(WorkshopDeleteType::class, null, ['id' => $company_id]);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()){
            $data = $request->getParsedBody()['form_workshop_delete'];
            // Change ID for interventions
            $this->logger->info("[Supression d'un magasin] Transfère des interventions...");
            $this->getRepository(InterventionRepository::class)->updateBy(
                [
                    'company_id' => (int)$data['company_id']
                ], 'company_id', $company_id, false);
            // Change ID for Deposit
            $this->logger->info("[Supression d'un magasin] Transfère des débours...");
            $this->getRepository(DepositRepository::class)->updateBy(
                [
                    'company_id' => (int)$data['company_id']
                ], 'company_id', $company_id, false);
            $this->logger->info(sprintf("Suppression du magasin avec l'ID %s", $company_id));
            $this->getRepository(CompanyRespository::class)->delete($company_id);
            $this->addFlash('panel-info', 'Magasin supprimé avec succès.');
            return $this->redirectToRoute('dash_workshop');
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Magasins', $this->getUrlFor('dash_workshop'));
        $bds->addCrumb('Actions', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/workshop/delete.html.twig', [
            'form' => $form,
            'c' => $c,
            'workshopCount' => $workshopCount,
            'breadcrumbs' => $bds,
            'currentMenu' => 'workshop'
        ]);
    }
}