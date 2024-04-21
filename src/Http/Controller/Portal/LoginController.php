<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Controller\Portal;

use Awurth\Validator\StatefulValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;
use Slim\App;

class LoginController extends AbstractController
{

    public function __construct(private readonly StatefulValidator $validator, App $app)
    {
        parent::__construct($app);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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
                            $this->setSession('customer', $customer);
                            return $this->redirectToRoute('portal_home');
                        }else {
                            $this->addFlash('error', "Ce compte en ligne n'est pas activé !");
                            return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
                        }
                    } else {
                        $this->addFlash('error', 'Compte client inconnu !');
                        return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
                    }
                }
            } else {
                foreach ($validator as $v) {
                    $this->addFlash('error', sprintf('%s', $v->getMessage()));
                }
                return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
            }
        }
        return $this->render($response, '/frontoffice/portal/login.html.twig');
        //return $response->withStatus(302)->withHeader('Location', $request->getServerParams()['HTTP_REFERER']);
    }
}