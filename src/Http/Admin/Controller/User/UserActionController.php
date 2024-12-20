<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Admin\Controller\User;

use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Domain\User\User;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\Admin\UserType;
use Selmak\Proaxive2\Infrastructure\Mailing\MailService;
use Selmak\Proaxive2\Infrastructure\Security\RandomNumberService;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserActionController extends AbstractController
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
    public function action(Request $request, Response $response, array $args): Response
    {
        $user_id = (int)$args['id'];
        if($user_id){
            $user = $this->getRepository(UserRepository::class)->find('id', $user_id, true);
            $form = $this->createForm(UserType::class, $user);
        } else {
            $form = $this->createForm(UserType::class);
        }
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getRequestData()['form_user'];
            unset($data['password_2']);
            $u = new User($data);
            if($user_id){
                // fix 2.0.8 -> empty ID for DTO
                $u->setId($user_id);
                $saveUpdate = $this->getRepository(UserRepository::class)->update($u, $user_id);
                if($saveUpdate){
                    $this->addFlash('panel-info', sprintf("L'utilisateur - %s - a bien été modifié.", $u->getFullname()));
                    return $this->redirectToReferer($request);
                }
            } else {
                // Generate token and code (confirm_at)
                $checkIfExist = $this->getRepository(UserRepository::class)->ifExist('mail', $u->getMail());
                if($checkIfExist == 1){
                    $this->addFlash('panel-error', sprintf("L'adresse courriel [%s] est déjà enregistrée.", $u->getMail()));
                } else {
                    $t = new RandomNumberService();
                    $token = $t->token(30);
                    $code = rand(7, 9999999);
                    $u->setToken($token);
                    $u->setConfirmAt((string)$code);
                    $mail = new MailService($this->getParameters('mailer'));
                    $mail->sendMail($u->getMail(), $this->view('mailer/security/your_account.html.twig', ['data' => $u, 'token' => $token, 'code' => $code]), 'Votre compte utilisateur Proaxive.');
                    $save = $this->getRepository(UserRepository::class)->add($u, true);
                    if($save){
                        $this->addFlash('panel-info', sprintf("L'utilisateur - %s - a bien été créé.", $u->getFullname()));
                        return $this->redirectToRoute('dash_user');
                    }
                }
            }
        }
        // Breadcrumbs
        $bds = $this->breadcrumbs;
        $bds->addCrumb('Accueil', $this->getUrlFor('dash_home'));
        $bds->addCrumb('Utilisateurs', $this->getUrlFor('dash_user'));
        $bds->addCrumb('Modification', false);
        $bds->render();
        // .Breadcrumbs
        return $this->render($response, 'backoffice/user/action.html.twig', [
            'form' => $form,
            'currentMenu' => 'user',
            'breadcrumbs' => $bds,
            'u' => $user
        ]);
    }
}