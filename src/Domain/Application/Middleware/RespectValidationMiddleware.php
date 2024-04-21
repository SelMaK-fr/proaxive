<?php

namespace Selmak\Proaxive2\Domain\Application\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;

class RespectValidationMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly ResponseFactoryInterface $responseFactory){}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (NestedValidationException $exception) {
            $messages = [];
            /** @var ValidationException $message */
            foreach ($exception->getIterator() as $message) {
                $key = $message->getParam('name');
                if ($key === null) {
                    continue;
                }
                $messages[$key] = $message->getMessage();
            }

            $response = $this->responseFactory->createResponse();

            $result = [
                'error' => [
                    'message' => $exception->getMessage(),
                    'details' => $messages,
                ],
            ];
            $response->getBody()->write(json_encode($result));
            $response->withHeader('Content-Type', 'application/json');

            return $response->withStatus(422);
        }
    }
}