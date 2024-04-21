<?php

namespace Selmak\Proaxive2\Http\API\V1\Auth;

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
        $token = [
            'sub' => $user['id'],
            'iss' => env('APP_DOMAIN'),
            'mail' => $user['mail'],
            'name' => $user['fullname'],
            'iat' => time(),
            'exp' => time() + (7*24*60*60),
        ];
        $t = JWT::encode($token, env('SECRET_KEY'), 'HS256');
        $message = [
            'Authorization' => 'Bearer ' . $t,
        ];
        return $this->jsonResponse($response, 'success', $message, 200);
    }
}