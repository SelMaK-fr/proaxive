<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class CustomerAjaxController extends AbstractController
{
    public function search(Request $request, Response $response): Response
    {
        $customers = [];
        if($request->getMethod() === 'GET') {
            $customers = $this->getRepository(CustomerRepository::class)->search($request->getAttribute('key'));
        }

        return $this->render($response, 'backoffice/customer/search.html.twig', [
            'customers' => $customers,
            'currentMenu' => 'customer'
        ]);
    }

    public function updateNote(Request $request, Response $response, array $args): Response
    {
        if($request->getMethod() === 'POST'){
            $customer_id = (int)$args['id'];
            $data = $request->getParsedBody();
            $validator = $this->validator->validate($request, [
                'about' => [
                    'rules' => Validator::length(8,200)
                ]
            ]);
            if($validator->count() === 0){
                $this->getRepository(CustomerRepository::class)->update($data, $customer_id);
            }
        }
        return $response;
    }

}