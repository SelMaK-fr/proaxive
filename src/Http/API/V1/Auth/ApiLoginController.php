<?php

namespace Selmak\Proaxive2\Http\API\V1\Auth;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selmak\Proaxive2\Domain\User\Repository\UserRepository;
use Selmak\Proaxive2\Http\Controller\AbstractController;

class ApiLoginController extends AbstractController
{
    public function postApiLogin(Request $request, Response $response): Response
    {
        $input = (array)$request->getParsedBody();
        $data = json_decode((string) json_encode($input), false);
        if(!isset($data->mail)) {
            throw new \Exception('The field "email" is required.', 400);
        }
        if(!isset($data->password)) {
            throw new \Exception('The field "password" is required.', 400);
        }
        $user = $this->getRepository(UserRepository::class)->apiLoginUser($data->mail, $data->password);
        $date   = new DateTimeImmutable();
        $expire_at     = $date->modify('+6 minutes')->getTimestamp();
        $payload = [
            'sub' => $user['id'],
            'mail' => $user['mail'],
            'name' => $user['fullname'],
            'iat' => $date->getTimestamp(),
            'iss' => env('APP_DOMAIN'),
            'nbf'  => $date->getTimestamp(),
            'exp' => $expire_at,
        ];
        $t = JWT::encode($payload, env('APP_SECRET'), 'HS512');
        $response->getBody()->write(json_encode(['token' => $t]));
        return $response->withHeader('Content-Type', 'application/json');
    }

}