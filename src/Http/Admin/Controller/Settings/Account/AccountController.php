<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\Settings\Account;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Account\Repository\AccountRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\AccountType;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AccountController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(Request $request, Response $response): Response
    {
        $id = $this->getUserId();
        $a = $this->getRepository(AccountRepository::class)->find('id', $id);

        $form = $this->createForm(AccountType::class, $a);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_account'];
            $save = $this->getRepository(AccountRepository::class)->update($data, $id);
            if($save){
                $this->addFlash('panel-info', "Votre compte utilisateur a bien été mis à jour.");
                return $this->redirectToRoute('dash_account');
            }
        }
        //
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Paramètres', false);
        $bds->addCrumb('Mon compte', false);
        $bds->render();
        //
        return $this->render($response, 'backoffice/settings/account/index.html.twig', [
           'currentMenu' => 'settings',
           'user' => $a,
           'form' => $form,
           'breadcrumbs' => $bds,
           'setting_current' => 'account'
        ]);
    }
}