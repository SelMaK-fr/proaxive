<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Deposit;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Deposit\Repository\DepositRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DepositReadController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function read(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'GET'){
            $form = $request->getQueryParams();
            $intervention = $form['intervention_reference'];
            $deposit = $form['deposit_reference'];
            $isSigned = $form['is_signed'];
            $d = $this->getRepository(DepositRepository::class)->findByReference($deposit);
            if($d->reference != $deposit OR $d->intervention_number != $intervention){
                return $this->redirectToRoute('intervention_update', ['id' => $d->interventions_id]);
            }
            // Breadcrumbs
            $breadcrumbs = $this->breadcrumbs;
            $breadcrumbs->addCrumb('Accueil', $this->getUrlFor('dash_home'));
            $breadcrumbs->addCrumb('Dépôt', false);
            $breadcrumbs->addCrumb($this->sanitize($deposit), false);
            $breadcrumbs->render();
            // .Breadcrumbs
            return $this->render($response, 'backoffice/deposit/read.html.twig', [
                'd' => $d,
                'is_signed' => $isSigned,
                'breadcrumbs' => $breadcrumbs,
                'currentMenu' => 'intervention'
            ]);
        }
        return new \Slim\Psr7\Response();
    }
}