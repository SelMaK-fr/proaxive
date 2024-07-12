<?php
declare (strict_types = 1);
namespace Selmak\Proaxive2\Http\Controller\Account;

use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use PragmaRX\Google2FA\Google2FA;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Domain\Auth\SessionUser;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Factory\CookieFactory;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Selmak\Proaxive2\Http\Type\AccountPasswordType;
use Slim\App;

final class UserAccountController extends AbstractController
{

    public function getSignIn(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST'){
            $params = $request->getParsedBody();
            $mail = v::email()->validate($params['email']);
            if($mail){
                $user = $this->getRepository(UserRepository::class);
                $data = $user->findUserByMail($params['email']);
                if ($data['mail'] == $params['email'] AND password_verify($params['password'], $data['password'])) {

                    if($data['key_totp']){
                        $this->setSession('auth_in_progress', $data);
                        return $this->redirectToRoute('auth_user_2fa');
                    }
                    //$this->getSession('auth');
                    $this->setSession('auth', $data);
                    // Create Cookie 3 days
                    $cookie = new CookieFactory();
                    $cookie->setCookie($data, $this->query);
                    return $this->redirectToRoute('dash_home');
                }
            } else {
                $this->addFlash('error', 'Le mot de passe ou le courriel ne correspond pas !');
            }
        }
        return $this->render($response, 'security/user/signin.html.twig');
    }

    /**
     * 2FA Auth
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function signTotp(Request $request, Response $response): Response
    {
        $session = $this->getSession('auth_in_progress');
        $secret = $session['key_totp'];
        $dataForm = $request->getParsedBody()['form_auth_totp'];
        $code = $dataForm['code'];
        if($request->getMethod() === 'POST'){
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($secret, $code);
            if($valid){
                $user = $this->getRepository(UserRepository::class);
                $data = $user->findUserByMail($session['mail']);
                $this->setSession('auth', $data);
                $this->deleteSession('auth_in_progress');
                $cookie = new CookieFactory();
                $cookie->setCookie($data, $this->query);
                return $this->redirectToRoute('dash_home');
            } else {
                $this->addFlash('error', 'Le code ne correspond pas !');
            }
        }

        return $this->render($response, 'security/user/signTotp.html.twig');
    }

    public function firstSignIn(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $user = $this->getRepository(UserRepository::class)->findUserByToken($params['token'], $params['code']);
        if(!$user){
            $this->addFlash('error', "Ce compte utilisateur n'existe pas ou est déjà configuré !");
            return $this->redirectToRoute('auth_user_login');
        }
        $form = $this->createForm(AccountPasswordType::class);
        $form->handleRequest();
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getRequestData()['form_account_password'];
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $data['confirm_at'] = '';
            unset($data['password_2']);
            $this->getRepository(UserRepository::class)->update($data, $user['id']);
            $this->addFlash('info', 'Votre mot de passe a bien été sauvegardé.');
            return $this->redirectToRoute('auth_user_login');
        }
        return $this->render($response, 'security/user/first_signin.html.twig', [
            'form' => $form
        ]);
    }

    public function logout($request, $response)
    {
        if($this->getSession('auth')) {
            $this->deleteSession('auth');
            setcookie('proaxive2-auth', '', -1, '/');
        }
        return $response->withStatus(302)->withHeader('Location', $this->getUrlFor('auth_user_login'));
    }
}