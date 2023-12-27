<?php
declare (strict_types = 1);
namespace App\Controller\Account;

use App\AbstractController;
use App\Type\AccountType;
use App\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Factory\CookieFactory;

final class UserAccountController extends AbstractController
{

    public function getSignUp(Request $request, Response $response): Response
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
        return $this->render($response, 'security/user/signup.html.twig');
    }
}