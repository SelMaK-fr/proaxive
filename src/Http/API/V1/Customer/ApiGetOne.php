<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\API\V1\Customer;

use Envms\FluentPDO\Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\Customer\Customer;
use Selmak\Proaxive2\Domain\Customer\Repository\CustomerRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

final class ApiGetOne extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $customer = $this->getRepository(CustomerRepository::class)->find('id', (int)$args['id']);
        if(!$customer){
            $customer = [
                'status' => 404,
                'message' => 'This customer not exist'
            ];
        }
        $data = [
            'mail' => $customer->mail,
            'fullname' => $customer->fullname,
            'login_id' => $customer->login_id,
            'phone' => $customer->phone,
            'mobile' => $customer->mobile,
            'localization' => [
                'department' => $customer->department,
                'address' => $customer->address,
                'zipcode' => $customer->zipcode,
                'city' => $customer->city,
                'geo_localization' => [
                    'addr_longitude' => $customer->addr_longitude,
                    'addr_latitude' => $customer->addr_latitude,
                ]
            ]
        ];
        return new JsonResponse($data, 200, []);
    }
}