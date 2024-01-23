<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Factory\RandomStringGeneratorFactory;

class CustomerActionController extends AbstractController
{

        /**
         * @param Request $request
         * @param Response $response
         * @param array $arg
         * @return RedirectResponse
         * @throws \Envms\FluentPDO\Exception
         */
        public function create(Request $request, Response $response, array $arg): Response
        {
            if($request->getMethod() === 'POST'){
                $data = $request->getParsedBody()['form_customer'];
                $query = $request->getQueryParams();
                if($query['s']){
                    $data['is_society'] = 1;
                }
                $data['activated'] = 1;
                $generateClientId = new RandomStringGeneratorFactory();
                $data['login_id'] = 'C-' . $generateClientId->generate(9);
                $checkIfExist = $this->getRepository(CustomerRepository::class)->ifExist('mail', $data['mail']);
                if($checkIfExist){
                    $this->session->getFlash()->add('panel-error', "Un compte client existe déjà avec cette adresse courriel.");
                } else {
                    $save = $this->getRepository(CustomerRepository::class)->add($data, true);
                    if($save){
                        $lastId = $this->getRepository(CustomerRepository::class)->lastInsertId();
                        $this->session->getFlash()->add('panel-success', sprintf('Le nouveau client <b> %s </b> a bien été créé', $data['fullname']));
                        return $this->redirectToRoute('customer_read', ['id' => $lastId]);
                    }
                }
            }
            return $this->redirectToReferer($request);
        }
}