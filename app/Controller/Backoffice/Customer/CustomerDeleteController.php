<?php
declare(strict_types=1);
namespace App\Controller\Backoffice\Customer;

use App\AbstractController;
use App\Repository\CustomerRepository;
use Awurth\Validator\ValidatorInterface;
use Envms\FluentPDO\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator;

class CustomerDeleteController extends AbstractController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $customer_id = (int)$args['id'];
        $customer = $this->getRepository(CustomerRepository::class)->find('id', $customer_id);
        if($request->getMethod() === 'DELETE'){
            $data = $request->getParsedBody();
            if($data['fullname'] === $customer->fullname){
                $this->getRepository(CustomerRepository::class)->delete($customer_id);
                $this->addFlash('panel-info', 'Le client a bien été supprimé');
                return $this->redirectToRoute('dash_customer');
            } else {
                $this->addFlash('panel-error', 'Le nom ne correspond pas !');
                return $this->redirectToReferer($request);
            }
        }
        return $this->redirectToRoute('dash_customer');
    }
}