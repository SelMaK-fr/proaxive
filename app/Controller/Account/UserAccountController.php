<?php
declare (strict_types = 1);
namespace App\Controller\Account;

use App\AbstractController;
use App\Repository\UserRepository;
use App\Service\MailService;
use App\Type\AccountPasswordType;
use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Factory\CookieFactory;
use Slim\App;

final class UserAccountController extends AbstractController
{

    public function __construct(App $app, private readonly SessionInterface $session, private readonly Query $query)
    {
        parent::__construct($app);
    }

    public function getSignIn(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST'){
            $params = $request->getParsedBody();
            $mail = v::email()->validate($params['email']);
            if($mail){
                $user = $this->getRepository(UserRepository::class);
                $data = $user->findUserByMail($params['email']);
                if ($data['mail'] == $params['email'] AND password_verify($params['password'], $data['password'])) {
                    $this->session->get('auth');
                    $this->session->set('auth', $data);
                    // Create Cookie 7 days
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
        if($this->session->get('auth')) {
            $this->session->delete('auth');
            setcookie('proaxive2-auth', '', -1, '/');
        }
        return $response->withStatus(302)->withHeader('Location', $this->getUrlFor('auth_user_login'));
    }
}