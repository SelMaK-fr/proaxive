<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Settings\Account;

use App\AbstractController;
use App\Repository\AccountRepository;
use App\Type\AccountType;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        $id = $this->session->get('auth')['id'];
        $a = $this->getRepository(AccountRepository::class)->find('id', $id);

        $form = $this->createForm(AccountType::class, $a);
        $form->handleRequest();

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_account'];
            $save = $this->getRepository(AccountRepository::class)->update($data, $id);
            if($save){
                $this->session->getFlash()->add('panel-info', "Votre compte utilisateur a bien été mis à jour.");
                return $this->redirectToRoute('dash_account');
            }
        }

        return $this->render($response, 'backoffice/settings/account/index.html.twig', [
           'currentMenu' => 'settings',
           'user' => $a,
           'form' => $form,
           'setting_current' => 'account'
        ]);
    }
}