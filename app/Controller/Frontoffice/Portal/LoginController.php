<?php
declare(strict_types=1);
namespace App\Controller\Frontoffice\Portal;

use App\AbstractController;
use App\Repository\CustomerRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class LoginController extends AbstractController
{

    public function index(Request $request, Response $response): Response
    {
        if($request->getMethod() === 'POST') {
            $params = $request->getParsedBody();
            $validator = $this->validator->validate($params, [
                'passwd' => [
                    'rules' => v::length(6,20)
                ]
            ]);
            if($validator->count() === 0) {
                $customer = $this->getRepository(CustomerRepository::class)->getByClientID($params['login_id']);
                if($customer) {
                    if (($customer['login_id'] OR $customer['mail']) == $params['login_id'] AND password_verify($params['passwd'], $customer['passwd'])) {
                        if($customer['enable_portal'] === 1) {
                            $this->session->set('customer', $customer);
                            return $this->redirectToRoute('portal_home');
                        }else {
                            $this->session->getFlash()->add('error', "Ce compte en ligne n'est pas activé !");
                            return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
                        }
                    } else {
                        $this->session->getFlash()->add('error', 'Compte client inconnu !');
                        return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
                    }
                }
            } else {
                foreach ($validator as $v) {
                    $this->session->getFlash()->add('error', sprintf('%s', $v->getMessage()));
                }
                return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
            }
        }
        return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
    }
}